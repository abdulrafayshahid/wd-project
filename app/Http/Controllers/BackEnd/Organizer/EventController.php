<?php

namespace App\Http\Controllers\BackEnd\Organizer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Event\StoreRequest;
use App\Http\Requests\Event\UpdateRequest;
use App\Models\City;
use App\Models\Country;
use App\Models\Language;
use App\Models\Event;
use App\Models\Organizer;
use App\Models\Event\EventImage;
use App\Models\Event\EventContent;
use App\Models\Event\EventDates;
use App\Models\Event\Ticket;
use App\Models\State;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Mews\Purifier\Facades\Purifier;
use DateTime;
use DatePeriod;
use DateInterval;


class EventController extends Controller
{
  //index
  public function index(Request $request)
  {
    $information['langs'] = Language::all();

    $language = Language::where('code', $request->language)->firstOrFail();
    $information['language'] = $language;

    $event_type = request()->input('event_type');
    $title = null;
    if (request()->filled('title')) {
      $title = request()->input('title');
    }
    $user = Organizer::where('id', Auth::guard('organizer')->user()->id)->first('created_by')->created_by;


    if (empty($user)) {


      $events = Event::join('event_contents', 'event_contents.event_id', '=', 'events.id')
        ->join('event_categories', 'event_categories.id', '=', 'event_contents.event_category_id')
        ->where('event_contents.language_id', '=', $language->id)
        ->when($title, function ($query) use ($title) {
          return $query->where('event_contents.title', 'like', '%' . $title . '%');
        })
        ->where('events.organizer_id', '=', Auth::guard('organizer')->user()->id)
        ->where('events.admin_approval', '=', 1)
        ->when($event_type, function ($query, $event_type) {
          return $query->where('events.event_type', $event_type);
        })
        ->select('events.*', 'event_contents.id as eventInfoId', 'event_contents.title', 'event_contents.slug', 'event_categories.name as category')
        ->orderByDesc('events.id')
        ->paginate(10);
    } else {
      $events = Event::join('event_contents', 'event_contents.event_id', '=', 'events.id')
        ->join('event_categories', 'event_categories.id', '=', 'event_contents.event_category_id')
        ->where('event_contents.language_id', '=', $language->id)
        ->when($title, function ($query) use ($title) {
          return $query->where('event_contents.title', 'like', '%' . $title . '%');
        })
        ->where('events.organizer_id', '=', $user)
        ->where('events.admin_approval', '=', 1)
        ->when($event_type, function ($query, $event_type) {
          return $query->where('events.event_type', $event_type);
        })
        ->select('events.*', 'event_contents.id as eventInfoId', 'event_contents.title', 'event_contents.slug', 'event_categories.name as category')
        ->orderByDesc('events.id')
        ->paginate(10);
    }
    $information['events'] = $events;
    return view('organizer.event.index', $information);
  }

  //choose_event_type
  public function choose_event_type()
  {
    return view('organizer.event.event_type');
  }

  //online_event
  public function add_event()
  {
    // get all the languages from db
    $languages = Language::get();
    $countries = Country::get();
    $information['getCurrencyInfo'] = $this->getCurrencyInfo();
    $information['languages'] = $languages;
    $information['countries'] = $countries;
    return view('organizer.event.create', $information);
  }

  //city_state
  public function city_state($id)
  {
    $city = City::where('country_id', $id)->orderBy('name', 'asc')->get();
    $state = State::where('country_id', $id)->orderBy('name', 'asc')->get();

    $result = [];
    $result['city'] = $city;
    $result['state'] = $state;
    return $result;
  }

  public function gallerystore(Request $request)
  {
    $img = $request->file('file');
    $allowedExts = array('jpg', 'png', 'jpeg');

    $rules = [
      'file' => [
        'dimensions:width=1170,height=570',
        function ($attribute, $value, $fail) use ($img, $allowedExts) {
          $ext = $img->getClientOriginalExtension();
          if (!in_array($ext, $allowedExts)) {
            return $fail("Only png, jpg, jpeg images are allowed");
          }
        },
      ]
    ];
    $messages = [
      'file.dimensions' => 'The file has invalid image dimensions ' . $img->getClientOriginalName()
    ];
    $validator = Validator::make($request->all(), $rules, $messages);
    if ($validator->fails()) {
      $validator->getMessageBag()->add('error', 'true');
      return response()->json($validator->errors());
    }
    $filename = uniqid() . '.jpg';
    $img->move(public_path('assets/admin/img/event-gallery/'), $filename);
    $pi = new EventImage;
    if (!empty($request->event_id)) {
      $pi->event_id = $request->event_id;
    }
    $pi->image = $filename;
    $pi->save();
    return response()->json(['status' => 'success', 'file_id' => $pi->id]);
  }

  public function imagermv(Request $request)
  {
    $pi = EventImage::where('id', $request->fileid)->first();
    @unlink(public_path('assets/admin/img/event-gallery/') . $pi->image);
    $pi->delete();
    return $pi->id;
  }


  // public function store(StoreRequest $request)
  // {
  //   DB::transaction(function () use ($request) {

  //     //calculate duration
  //     if ($request->date_type == 'single') {
  //       $start = Carbon::parse($request->start_date . $request->start_time);
  //       $end = Carbon::parse($request->end_date . $request->end_time);
  //       $diffent = DurationCalulate($start, $end);
  //     }
  //     //calculate duration end

  //     $in = $request->all();
  //     $in['duration'] = $request->date_type == 'single' ? $diffent : '';

  //     $img = $request->file('thumbnail');

  //     $user = Organizer::where('id', Auth::guard('organizer')->user()->id)->first('created_by')->created_by;
  //     if (empty($user)) {
  //       $in['organizer_id'] = Auth::guard('organizer')->user()->id;
  //     } else {
  //       $in['organizer_id'] = $user;
  //     }
  //     if ($request->hasFile('thumbnail')) {
  //       $filename = time() . '.' . $img->getClientOriginalExtension();
  //       $directory = public_path('assets/admin/img/event/thumbnail/');
  //       @mkdir($directory, 0775, true);
  //       $request->file('thumbnail')->move($directory, $filename);
  //       $in['thumbnail'] = $filename;
  //     }
  //     $in['f_price'] = $request->price;
  //     $in['end_date_time'] = Carbon::parse($request->end_date . ' ' . $request->end_time);
  //     if (isset($in['is_featured_date'])) {
  //       $in['is_featured_date'] = json_encode($in['is_featured_date']);
  //     }
  //     $event = Event::create($in);

  //     if ($request->date_type == 'multiple') {
  //       $i = 1;
  //       foreach ($request->m_start_date as $key => $date) {
  //         $start = Carbon::parse($date . $request->m_start_time[$key]);
  //         $end = Carbon::parse($request->m_end_date[$key] . $request->m_end_time[$key]);
  //         $diffent = DurationCalulate($start, $end);

  //         EventDates::create([
  //           'event_id' => $event->id,
  //           'start_date' => $date,
  //           'start_time' => $request->m_start_time[$key],
  //           'end_date' => $request->m_end_date[$key],
  //           'end_time' => $request->m_end_time[$key],
  //           'duration' => $diffent,
  //           'start_date_time' => $start,
  //           'end_date_time' => $end,
  //         ]);
  //         if ($i == 1) {
  //           $event->update([
  //             'duration' => $diffent
  //           ]);
  //         }
  //         $i++;
  //       }

  //       //update event date time
  //       $event_date = EventDates::where('event_id', $event->id)->orderBy('end_date_time', 'desc')->first();

  //       $event->end_date_time = $event_date->end_date_time;
  //       $event->save();
  //     }


  //     $in['event_id'] = $event->id;
  //     if ($request->event_type == 'online') {
  //       if (!$request->pricing_type) {
  //         $in['pricing_type'] = 'normal';
  //       }
  //       $in['early_bird_discount'] = $request->early_bird_discount_type;
  //       $in['early_bird_discount_type'] = $request->discount_type;
  //       $ticket = Ticket::create($in);
  //     }

  //     $slders = $request->slider_images;

  //     foreach ($slders as $key => $id) {
  //       $event_image = EventImage::where('id', $id)->first();
  //       if ($event_image) {
  //         $event_image->event_id = $event->id;
  //         $event_image->save();
  //       }
  //     }
  //     $languages = Language::all();

  //     foreach ($languages as $language) {
  //       $event_content = new EventContent();
  //       $event_content->language_id = $language->id;
  //       $event_content->event_category_id = $request[$language->code . '_category_id'];
  //       $event_content->event_id = $event->id;
  //       $event_content->title = $request[$language->code . '_title'];
  //       if ($request->event_type == 'venue') {
  //         $transport = explode(",", $request[$language->code . "_transport"]);
  //         $type_of_transport = explode(",", $request[$language->code . "_type_of_transport"]);

  //         $transport_array = [];
  //         for ($i = 0; $i < count($transport); $i++) {
  //           if (isset($transport[$i]) && !isset($transport_array[$transport[$i]])) {
  //             $transport_array[$transport[$i]] = isset($type_of_transport[$i]) ? $type_of_transport[$i] : null;
  //           }


  //         }

  //         $activity = [
  //           "activity" => $request->input('activity_required'),
  //           "sleep" => $request->input('sleep_required'),
  //           "accomodation" => $request->input('accommodation_included')
  //       ];



  //       $fooddrink = [
  //         "foodDrink" => $request->input('food_drink_required'),
  //         "Food" => [
  //           "food" => $request->input('food_required'),
  //           "Type_of_meal" => $request->input('Type_of_meal'),
  //           "Time_of_day" => $request->input('Time_of_day'),
  //           "Tags" => $request->input('Type_of_tag'),
  //           "Dietary" =>[
  //             "Diabetic"=> $request->input('Diabetic'),
  //             "Egg_free"=> $request->input('Egg_free'),
  //             "Gluten_free"=> $request->input('Gluten_free'),
  //             "Halal"=> $request->input('Halal'),
  //             "Keto"=> $request->input('Keto'),
  //             "Kosher"=> $request->input('Kosher'),
  //             "Lactose_free"=> $request->input('Lactose_free'),
  //             "Low_carb"=> $request->input('Low_carb'),
  //             "Seafood_fish_free"=> $request->input('Seafood_fish_free'),
  //             "Pescatarian"=> $request->input('Pescatarian'),
  //             "Vegan"=> $request->input('Vegan'),
  //             "Vegetarian"=> $request->input('Vegetarian'),
  //           ]
  //         ],
  //         "Drink" => [
  //           "drink" => $request->input('drink_required'),
  //           "drink_Tags" => $request->input('drink_Tags'),
  //         ]
  //     ];



  //     $event_content->inclusions_activity_one = $request['inclusions_activity_one'];
  //     $event_content->inclusions_activity_two = $request['inclusions_activity_two'];
  //     $event_content->inclusions_activity_three = $request['inclusions_activity_three'];

  //     $event_content->inclusion_full_description = $request['inclusion_full_description'];
  //     $event_content->inclusion_short_description = $request['inclusion_short_description'];
  //     $event_content->main_inclusions = $request['main_inclusions'];
  //     $event_content->inclusion_optional_description = $request['inclusion_optional_description'];

  //         $transportation = json_encode($transport_array);
  //         $event_content->address = $request[$language->code . '_address'];
  //         $event_content->country = $request[$language->code . '_country'];
  //         $event_content->state = $request[$language->code . '_state'];
  //         $event_content->city = $request[$language->code . '_city'];
  //         $event_content->zip_code = $request[$language->code . '_zip_code'];
  //         $event_content->transportation = $transportation;
  //         $event_content->Guide_activity_info = json_encode($activity);
  //         $event_content->food_drink = json_encode($fooddrink);
  //       }
  //       $event_content->slug = createSlug($request[$language->code . '_title']);
  //       $event_content->description = Purifier::clean($request[$language->code . '_description'], 'youtube');
  //       $event_content->refund_policy = $request[$language->code . '_refund_policy'];
  //       $event_content->meta_keywords = $request[$language->code . '_meta_keywords'];
  //       $event_content->meta_description = $request[$language->code . '_meta_description'];

  //       $event_content->save();
  //     }

  //   });
  //   $new_ev = Event::latest()->first();

  //   Session::flash('success', 'New Event added successfully!');
  //   return response()->json(['status' => 'success', 'data' => $new_ev], 200);
  // }

  public function store(StoreRequest $request)
  {
    DB::transaction(function () use ($request) {

      //calculate duration
      if ($request->date_type == 'single') {
        $start = Carbon::parse($request->start_date . $request->start_time);
        $end = Carbon::parse($request->end_date . $request->end_time);
        $diffent = DurationCalulate($start, $end);
      }
      //calculate duration end

      $in = $request->all();



      if($request->date_type == 'new'){
        // $start_time = [];
        // $end_time = [];

        // $newarr = array_filter($request->m_start_time_day, fn ($value) => !is_null($value));
        // $newarrend = array_filter($request->m_end_time_day, fn ($value) => !is_null($value));


        // foreach ($newarr as $key => $id) {
        //   array_push($start_time, $id);

        // }

        // foreach ($newarrend as $key => $id) {
        //   array_push($end_time, $id);

        // }

        $in['start_date'] = $request->m_start_date1;
        
        $in['end_date'] = $request->m_end_date1;


      }
      $in['duration'] = $request->date_type == 'single' ? $diffent : '';

      $img = $request->file('thumbnail');

      $user = Organizer::where('id', Auth::guard('organizer')->user()->id)->first('created_by')->created_by;
      if (empty($user)) {
        $in['organizer_id'] = Auth::guard('organizer')->user()->id;
      } else {
        $in['organizer_id'] = $user;
      }
      if ($request->hasFile('thumbnail')) {
        $filename = time() . '.' . $img->getClientOriginalExtension();
        $directory = public_path('assets/admin/img/event/thumbnail/');
        @mkdir($directory, 0775, true);
        $request->file('thumbnail')->move($directory, $filename);
        $in['thumbnail'] = $filename;
      }
      $in['f_price'] = $request->price;
      $in['end_date_time'] = Carbon::parse($request->end_date . ' ' . $request->end_time);
      if (isset($in['is_featured_date'])) {
        $in['is_featured_date'] = json_encode($in['is_featured_date']);
      }
      $event = Event::create($in);

      if ($request->date_type == 'multiple') {
        $i = 1;
        foreach ($request->m_start_date as $key => $date) {
          $start = Carbon::parse($date . $request->m_start_time[$key]);
          $end = Carbon::parse($request->m_end_date[$key] . $request->m_end_time[$key]);
          $diffent = DurationCalulate($start, $end);

          EventDates::create([
            'event_id' => $event->id,
            'start_date' => $date,
            'start_time' => $request->m_start_time[$key],
            'end_date' => $request->m_end_date[$key],
            'end_time' => $request->m_end_time[$key],
            'duration' => $diffent,
            'start_date_time' => $start,
            'end_date_time' => $end,
          ]);
          if ($i == 1) {
            $event->update([
              'duration' => $diffent
            ]);
          }
          $i++;
        }

        //update event date time
        $event_date = EventDates::where('event_id', $event->id)->orderBy('end_date_time', 'desc')->first();

        $event->end_date_time = $event_date->end_date_time;
        $event->save();
      }

      if ($request->date_type == 'new') {

        $begin = new DateTime($request->m_start_date1);
        //$begin->format('d/m/Y');
        $end   = new DateTime($request->m_end_date1);
        //$end->format('d/m/Y');

        $j = 0;
        // $start_time = [];
        // $end_time = [];

        // $newarr = array_filter($request->m_start_time_day, fn ($value) => !is_null($value));
        // $newarrend = array_filter($request->m_end_time_day, fn ($value) => !is_null($value));


        // foreach ($newarr as $key => $id) {
        //   array_push($start_time, $id);

        // }

        // foreach ($newarrend as $key => $id) {
        //   array_push($end_time, $id);

        // }

        $interval = \DateInterval::createFromDateString('1 day');
        $period = new \DatePeriod($begin, $interval, $end);
        $dates = [];

        for($i = $begin; $i <= $end; $i->modify('+1 day')){

          array_push($dates, $i->format('Y-m-d'));


        }

        foreach ($dates as $key => $i) {

          foreach ($request->m_start_time_day["'".$i."'"] as $key1 => $i1) {

          $start = Carbon::parse($i .' '.  $i1.':00');

          $end = Carbon::parse(new DateTime($i .' '.  $request->m_end_time_day["'".$i."'"][$key1] .':00'));


          $diffent = DurationCalulate($start, $end);

          EventDates::create([
            'event_id' => $event->id,
            'start_date' => $i,
            'start_time' => $i1,
            'end_date' => $i,
            'end_time' => $request->m_end_time_day["'".$i."'"][$key1],
            'duration' => $diffent,
            'start_date_time' => $i .' '. $i1 . ':00',
            'end_date_time' => $end
          ]);

            $diffent1 = DurationCalulate(Carbon::parse($dates[0] .' '. $request->m_start_time_day["'".$dates[0]."'"][0]), $end);
            $end_t = new DateTime($end);
            $event->update([
              'duration' => $diffent1,
              'start_time' => $request->m_start_time_day["'".$dates[0]."'"][0],
              'end_time' => $request->m_end_time_day["'".$i."'"][$key1],
              'end_date_time' =>$end
            ]);
          }
          
        }

        //update event date time
        $event_date = EventDates::where('event_id', $event->id)->orderBy('end_date_time', 'desc')->first();

        $event->end_date_time = $event_date->end_date_time;
        $event->save();
      }


      $in['event_id'] = $event->id;
      if ($request->event_type == 'online') {
        if (!$request->pricing_type) {
          $in['pricing_type'] = 'normal';
        }
        $in['early_bird_discount'] = $request->early_bird_discount_type;
        $in['early_bird_discount_type'] = $request->discount_type;
        $ticket = Ticket::create($in);
      }

      
      $languages = Language::all();

      foreach ($languages as $language) {
        $event_content = new EventContent();
        $event_content->language_id = $language->id;
        $event_content->event_category_id = $request[$language->code . '_category_id'];
        $event_content->event_id = $event->id;
        $event_content->title = $request[$language->code . '_title'];
        if ($request->event_type == 'venue') {
          $transport = explode(",", $request[$language->code . "_transport"]);
          $type_of_transport = explode(",", $request[$language->code . "_type_of_transport"]);

          $transport_array = [];
          for ($i = 0; $i < count($transport); $i++) {
            if (isset($transport[$i]) && !isset($transport_array[$transport[$i]])) {
              $transport_array[$transport[$i]] = isset($type_of_transport[$i]) ? $type_of_transport[$i] : null;
            }


          }
          $activity = [
            "activity" => $request->input('activity_required'),
            "sleep" => $request->input('sleep_required'),
            "accomodation" => $request->input('accommodation_included')
        ];



        $fooddrink = [
          "foodDrink" => $request->input('food_drink_required'),
          "Food" => [
            "food" => $request->input('food_required'),
            "Type_of_meal" => $request->input('Type_of_meal'),
            "Time_of_day" => $request->input('Time_of_day'),
            "Tags" => $request->input('Type_of_tag'),
            "Dietary" =>[
              "Diabetic"=> $request->input('Diabetic'),
              "Egg_free"=> $request->input('Egg_free'),
              "Gluten_free"=> $request->input('Gluten_free'),
              "Halal"=> $request->input('Halal'),
              "Keto"=> $request->input('Keto'),
              "Kosher"=> $request->input('Kosher'),
              "Lactose_free"=> $request->input('Lactose_free'),
              "Low_carb"=> $request->input('Low_carb'),
              "Seafood_fish_free"=> $request->input('Seafood_fish_free'),
              "Pescatarian"=> $request->input('Pescatarian'),
              "Vegan"=> $request->input('Vegan'),
              "Vegetarian"=> $request->input('Vegetarian'),
            ]
          ],
          "Drink" => [
            "drink" => $request->input('drink_required'),
            "drink_Tags" => $request->input('drink_Tags'),
          ]
      ];
          $transportation = json_encode($transport_array);
          $event_content->address = $request[$language->code . '_address'];
          $event_content->country = $request[$language->code . '_country'];
          $event_content->state = $request[$language->code . '_state'];
          $event_content->city = $request[$language->code . '_city'];
          $event_content->zip_code = $request[$language->code . '_zip_code'];
          $event_content->transportation = $transportation;


          $event_content->inclusions_activity_one = $request['inclusions_activity_one'];
          $event_content->inclusions_activity_two = $request['inclusions_activity_two'];
          $event_content->inclusions_activity_three = $request['inclusions_activity_three'];

          $event_content->inclusion_full_description = $request['inclusion_full_description'];
          $event_content->inclusion_short_description = $request['inclusion_short_description'];
          $event_content->main_inclusions = $request['main_inclusions'];
          $event_content->inclusion_optional_description = $request['inclusion_optional_description'];




          $event_content->Guide_activity_info = json_encode($activity);
          $event_content->food_drink = json_encode($fooddrink);

        }
        $event_content->slug = createSlug($request[$language->code . '_title']);
        $event_content->description = Purifier::clean($request[$language->code . '_description'], 'youtube');
        $event_content->refund_policy = $request[$language->code . '_refund_policy'];
        $event_content->meta_keywords = $request[$language->code . '_meta_keywords'];
        $event_content->meta_description = $request[$language->code . '_meta_description'];

        $event_content->save();
      }

    });
    $new_ev = Event::latest()->first();

    Session::flash('success', 'New Event added successfully!');
    return response()->json(['status' => 'success', 'data' => $new_ev], 200);
  }
  /**
   * Update status (active/DeActive) of a specified resource.
   *
   * @param \Illuminate\Http\Request $request
   * @param int $id
   * @return \Illuminate\Http\Response
   */
  public function updateStatus(Request $request, $id)
  {
    $event = Event::find($id);
    $user = Organizer::where('id', Auth::guard('organizer')->user()->id)->first('created_by')->created_by;
    if (empty($user)) {
      if (Auth::guard('organizer')->user()->id != $event->organizer_id) {
        return back();
      }
    } else {

      if ($user != $event->organizer_id) {
        return back();
      }
    }

    $event->update([
      'status' => $request['status']
    ]);
    Session::flash('success', 'Event status updated successfully!');

    return redirect()->back();
  }

  /**
   * Update featured status of a specified resource.
   *
   * @param \Illuminate\Http\Request $request
   * @param int $id
   * @return \Illuminate\Http\Response
   */
  public function updateFeatured(Request $request, $id)
  {
    $event = Event::find($id);
    $user = Organizer::where('id', Auth::guard('organizer')->user()->id)->first('created_by')->created_by;
    if (empty($user)) {
      if (Auth::guard('organizer')->user()->id != $event->organizer_id) {
        return back();
      }
    } else {
      if ($user != $event->organizer_id) {
        return back();
      }
    }


    if ($request['is_featured'] == 'yes') {
      $event->is_featured = 'yes';
      $event->save();

      Session::flash('success', 'Event Featured Successfully...!');
    } else {
      $event->is_featured = 'no';
      $event->save();

      Session::flash('success', 'Event Unfeatured Successfully..!');
    }

    return redirect()->back();
  }

  public function edit($id)
  {
    $user = Organizer::where('id', Auth::guard('organizer')->user()->id)->first('created_by')->created_by;
    if (empty($user)) {
      $event = Event::with('ticket')->where('id', $id)->firstOrFail();
      if (Auth::guard('organizer')->user()->id != $event->organizer_id) {
        return back();
      }
    } else {
      $event = Event::with('ticket')->where('id', $id)->firstOrFail();
      if ($user != $event->organizer_id) {
        return back();
      }
    }

    $user = Organizer::where('id', Auth::guard('organizer')->user()->id)->first('created_by')->created_by;
    if (empty($user)) {
      if ($event->organizer_id != Auth::guard('organizer')->user()->id) {
        return redirect()->route('organizer.dashboard');
      }
    } else {
      if ($event->organizer_id != $user) {
        return redirect()->route('organizer.dashboard');
      }
    }


    $information['event'] = $event;
    $information['getCurrencyInfo'] = $this->getCurrencyInfo();
    $information['languages'] = Language::all();
    $information['countries'] = Country::get();
    $information['cities'] = City::where('country_id', $event->country)->get();
    $information['states'] = State::where('country_id', $event->country)->get();

    return view('organizer.event.edit', $information);
  }

  public function imagedbrmv(Request $request)
  {
    $pi = EventImage::where('id', $request->fileid)->first();
    $event_id = $pi->event_id;
    $image_count = EventImage::where('event_id', $event_id)->get()->count();
    if ($image_count > 1) {
      @unlink(public_path('assets/admin/img/event-gallery/') . $pi->image);
      $pi->delete();
      return $pi->id;
    } else {
      return 'false';
    }
    @unlink(public_path('assets/admin/img/event-gallery/') . $pi->image);
    $pi->delete();
    return $pi->id;
  }

  public function images($portid)
  {
    $images = EventImage::where('event_id', $portid)->get();
    return $images;
  }

  public function update(UpdateRequest $request)
  {
    //calculate duration
    if ($request->date_type == 'single') {
      $start = Carbon::parse($request->start_date . $request->start_time);
      $end = Carbon::parse($request->end_date . $request->end_time);
      $diffent = DurationCalulate($start, $end);
    }
    //calculate duration end
    $img = $request->file('thumbnail');

    $in = $request->all();

    $event = Event::where('id', $request->event_id)->first();
    if ($request->hasFile('thumbnail')) {
      @unlink(public_path('assets/admin/img/event/thumbnail/') . $event->thumbnail);
      $filename = time() . '.' . $img->getClientOriginalExtension();
      @mkdir(public_path('assets/admin/img/event/thumbnail/'), 0775, true);
      $request->file('thumbnail')->move(public_path('assets/admin/img/event/thumbnail/'), $filename);
      $in['thumbnail'] = $filename;
    }

    $languages = Language::all();

    $i = 1;
    foreach ($languages as $language) {
      $event_content = EventContent::where('event_id', $event->id)->where('language_id', $language->id)->first();
      if (!$event_content) {
        $event_content = new EventContent();
      }
      $event_content->language_id = $language->id;
      $event_content->event_category_id = $request[$language->code . '_category_id'];
      $event_content->event_id = $event->id;
      $event_content->title = $request[$language->code . '_title'];
      if ($request->event_type == 'venue') {
        $event_content->address = $request[$language->code . '_address'];
        $event_content->country = $request[$language->code . '_country'];
        $event_content->state = $request[$language->code . '_state'];
        $event_content->city = $request[$language->code . '_city'];
        $event_content->zip_code = $request[$language->code . '_zip_code'];
      }
      $event_content->slug = createSlug($request[$language->code . '_title']);
      $event_content->description = Purifier::clean($request[$language->code . '_description'], 'youtube');
      $event_content->refund_policy = $request[$language->code . '_refund_policy'];
      $event_content->meta_keywords = $request[$language->code . '_meta_keywords'];
      $event_content->meta_description = $request[$language->code . '_meta_description'];
      $event_content->save();
    }
    if ($request->event_type == 'online') {
      if (!$request->pricing_type) {
        $pricing_type = 'normal';
      } else {
        $pricing_type = $request->pricing_type;
      }
      Ticket::where('event_id', $request->event_id)->update([
        'price' => $request->price,
        'f_price' => $request->price,
        'pricing_type' => $pricing_type,
        'ticket_available_type' => $request->ticket_available_type,
        'ticket_available' => $request->ticket_available,
        'max_ticket_buy_type' => $request->max_ticket_buy_type,
        'max_buy_ticket' => $request->max_buy_ticket,
        'early_bird_discount' => $request->early_bird_discount_type,
        'early_bird_discount_type' => $request->discount_type,
        'early_bird_discount_amount' => $request->early_bird_discount_amount,
        'early_bird_discount_date' => $request->early_bird_discount_date,
        'early_bird_discount_time' => $request->early_bird_discount_time,
      ]);
    }

    $event = Event::where('id', $event->id)->first();


    if ($request->date_type == 'multiple') {
      $i = 1;
      foreach ($request->m_start_date as $key => $date) {
        $start = Carbon::parse($date . $request->m_start_time[$key]);
        $end = Carbon::parse($request->m_end_date[$key] . $request->m_end_time[$key]);
        $diffent = DurationCalulate($start, $end);

        if (!empty($request->date_ids[$key])) {
          $event_date = EventDates::where('id', $request->date_ids[$key])->first();
          $event_date->start_date = $date;
          $event_date->start_time = $request->m_start_time[$key];
          $event_date->end_date = $request->m_end_date[$key];
          $event_date->end_time = $request->m_end_time[$key];
          $event_date->duration = $diffent;
          $event_date->start_date_time = $start;
          $event_date->end_date_time = $end;
          $event_date->save();
        } else {
          EventDates::create([
            'event_id' => $event->id,
            'start_date' => $date,
            'start_time' => $request->m_start_time[$key],
            'end_date' => $request->m_end_date[$key],
            'end_time' => $request->m_end_time[$key],
            'duration' => $diffent,
            'start_date_time' => $start,
            'end_date_time' => $end,
          ]);
        }

        if ($i == 1) {
          $event->update([
            'duration' => $diffent
          ]);
        }
        $i++;
      }
    }
    if (isset($in['is_featured_date'])) {
      $in['is_featured_date'] = json_encode($in['is_featured_date']);
    }

    if ($request->date_type == 'single') {
      $in['end_date_time'] = Carbon::parse($request->end_date . ' ' . $request->end_time);
      $in['duration'] = $diffent;
    } else {
      //update event date time
      $event_date = EventDates::where('event_id', $event->id)->orderBy('end_date_time', 'desc')->first();

      $in['end_date_time'] = $event_date->end_date_time;
    }

    $event->update($in);
    Session::flash('success', 'Event Updated successfully');
    return response()->json(['status' => 'success'], 200);
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param int $id
   * @return \Illuminate\Http\Response
   */
  public function destroy($id)
  {
    $event = Event::find($id);
    if (Auth::guard('organizer')->user()->id != $event->organizer_id) {
      return back();
    }

    @unlink(public_path('assets/admin/img/event/thumbnail/') . $event->thumbnail);

    $event_contents = EventContent::where('event_id', $event->id)->get();
    foreach ($event_contents as $event_content) {
      $event_content->delete();
    }
    $event_images = EventImage::where('event_id', $event->id)->get();
    foreach ($event_images as $event_image) {
      @unlink(public_path('assets/admin/img/event-gallery/') . $event_image->image);
      $event_image->delete();
    }

    //bookings
    $bookings = $event->booking()->get();
    foreach ($bookings as $booking) {
      // first, delete the attachment
      @unlink(public_path('assets/admin/file/attachments/') . $booking->attachment);

      // second, delete the invoice
      @unlink(public_path('assets/admin/file/invoices/') . $booking->invoice);

      $booking->delete();
    }

    //tickets
    $tickets = $event->tickets()->get();
    foreach ($tickets as $ticket) {
      $ticket->delete();
    }

    //wishlists
    $wishlists = $event->wishlists()->get();
    foreach ($wishlists as $wishlist) {
      $wishlist->delete();
    }

    // finally delete the course
    $event->delete();

    return redirect()->back()->with('success', 'Course deleted successfully!');
  }

  //bulk_delete
  public function bulk_delete(Request $request)
  {
    foreach ($request->ids as $id) {
      $event = Event::find($id);
      if (Auth::guard('organizer')->user()->id != $event->organizer_id) {
        return back();
      }

      @unlink(public_path('assets/admin/img/event/thumbnail/') . $event->thumbnail);

      $event_contents = EventContent::where('event_id', $event->id)->get();
      foreach ($event_contents as $event_content) {
        $event_content->delete();
      }
      $event_images = EventImage::where('event_id', $event->id)->get();
      foreach ($event_images as $event_image) {
        @unlink(public_path('assets/admin/img/event-gallery/') . $event_image->image);
        $event_image->delete();
      }

      //bookings
      $bookings = $event->booking()->get();
      foreach ($bookings as $booking) {
        // first, delete the attachment
        @unlink(public_path('assets/admin/file/attachments/') . $booking->attachment);

        // second, delete the invoice
        @unlink(public_path('assets/admin/file/invoices/') . $booking->invoice);

        $booking->delete();
      }

      //tickets
      $tickets = $event->tickets()->get();
      foreach ($tickets as $ticket) {
        $ticket->delete();
      }
      //wishlists
      $wishlists = $event->wishlists()->get();
      foreach ($wishlists as $wishlist) {
        $wishlist->delete();
      }

      // finally delete the course
      $event->delete();
    }
    Session::flash('success', 'Tickets are deleted successfully!');
    return response()->json(['status' => 'success'], 200);
  }

  public function new()
  {
    return "hello world!";
  }
}