<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CaHoc;
use App\Models\CaThu;
use App\Models\ThuHoc;
use DateInterval;
use DatePeriod;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class CaThuController extends Controller
{

    protected $v,  $cahoc, $thu, $cathu;
    public function __construct()
    {
        $this->v = [];
        $this->cahoc  = new CaHoc();
        $this->thu = new ThuHoc();
        $this->cathu = new CaThu();
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $this->v['params'] =  $request->all();
        $this->v['cahoc'] = $this->cahoc->index(null, false, null);
        $this->v['thu'] = $this->thu->index(null, false, null);
        $this->v['list'] = $this->cathu->index($this->v['params'], true, 10);



        return view('admin.lichhoc.index', $this->v);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        $this->v['ca'] = $this->cahoc->index(null, false, null);
        $this->v['thuhoc'] = $this->thu->index(null, false, null);
        if ($request->isMethod('POST')) {
            $params = [];

            $params['cols'] = array_map(function ($item) {
                if ($item == '') {
                    $item = null;
                }

                if (is_string($item)) {
                    $item = trim($item);
                }

                return $item;
            }, $request->post());
            // dd($request->all());

            unset($params['cols']['_token']);
            // dd($params['cols']);
            // dd(implode("," , $params['cols']['thu_hoc_id']));

            $res = $this->cathu->create($params);
            if ($res > 0) {
                Session::flash('success', 'Thêm thành công');
            } else {
                Session::flash('error', "Thêm không thành công");
            }

            return redirect()->route('route_BE_Admin_List_Ca_Thu');
        }

        return view('admin.lichhoc.add', $this->v);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show()
    {
        function createDatesTable($period, $start)
        {
            $calendarStr = '';
            foreach ($period as $key => $date_row) {
                if ($start % 7 == 0) {
                    $calendarStr .= '</tr><tr>';
                }

                $calendarStr .= '<td class="date">' . $date_row->format('d') . '</td>';
                $start++;
            }

            if ($start % 7 == 0) {
                $calendarStr .= '</tr>';
            } else {
                for ($i = 0; $i <= 6; $i++) {
                    if ($start % 7 != 0)
                        $calendarStr .= '<td class="empty_dates"></td>';
                    else
                        break;
                    $start++;
                }
                $calendarStr .= '</tr>';
            }

            return $calendarStr;
        }

        function createCalendarBetweenTwoDates($startTime, $endTime)
        {

            $calendarStr = '';
            $weekDays = array(
                'Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'
            );

            $calendarStr .= '<table class="table" >';

            $calendarStr .= '<tr><th class="week-days">' . implode('</th><th class="week-days">', $weekDays) . '</th></tr>';


            $period = new DatePeriod(
                new DateTime(date('Y-m-d', $startTime)),
                new DateInterval('P1D'),
                new DateTime(date('Y-m-d', $endTime))
            );

            $currentDay = array_search(date('D', $startTime), $weekDays);
            $start = 0;

            $calendarStr .= '<tr>';
            for ($i = $start; $i < $currentDay; $i++) {
                $calendarStr .= '<td class="empty date"></td>';
                $start++;
            }

            if ($currentDay < 6) {
                $calendarStr .= createDatesTable($period, $start);
            } else {
                $calendarStr .= createDatesTable($period, $start);
            }

            $calendarStr .= '</table>';

            return $calendarStr;
        }

        // $startTime = strtotime('+25 day', time());
        // $d = DateTime::createFromFormat(date('2022-11-17'),  '22-09-2008 00:00:00');
        // $d = $d->getTimestamp();
        $startTime = strtotime('+0 day', strtotime(date('2022-11-17')));
        $endTime = strtotime('+30 day', strtotime(date('2022-11-17')));
        // $endTime = strtotime('+30 day', time());
        $this->v['lich'] = createCalendarBetweenTwoDates($startTime, $endTime);
        // echo createCalendarBetweenTwoDates($startTime, $endTime);
        return view('admin.lichhoc.show', $this->v);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id, Request $request)
    {
        if ($id) {
            // id của ca
            $request->session()->put('id', $id);
            $this->v['ca'] = $this->cahoc->index(null, false, null);
            $this->v['res'] = $this->cathu->show($id);
            $this->v['thuhoc'] = $this->thu->index(null, false, null);
            $this->v['lichhoc'] = $this->cathu->show($id);
          
            $arrayThuTheoCa = explode(',', $this->v['lichhoc']->thu_hoc_id);
            $this->v['arrayThuTheoCa'] = $arrayThuTheoCa;
           
            return view('admin.lichhoc.update', $this->v);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $id = session('id');
        $params = [];
        $params['cols'] = array_map(function ($item) {
            if($item == ''){
                $item = null;
            }
            if (is_string($item)) {
                $item = trim($item);
            }
            return $item;
        }, $request->all());

        unset($params['cols']['_token']);
        $params['cols']['id']=  $id;
        // dd($params);
        // $this->v['lichhoc'] = $this->cathu->show($id);
          
        // $arrayThuTheoCa = [ $this->v['lichhoc']->thu_hoc_id ];
        // // dd($arrayThuTheoCa)
        // $arrayThuTheoCa->detach();
        $res = $this->cathu->saveupdate($params);
        if($res){
            Session::flash('success' , "Cập nhập thành công");

        }else {
            Session::flash('error', "Cập nhập không thành công");
        }


        return back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if ($id) {
            $res = $this->cathu->remove($id);
            if ($res) {
                Session::flash('success', 'Xóa thành công');
            } else {
                Session::flash('error', "Xóa không thành công");
            }
            return back();
        }
    }


    public function destroyAll(Request $request)
    {

        if ($request->isMethod('POST')) {
            $params = [];
            $params['cols'] = array_map(function ($item) {
                return $item;
            }, $request->all());
            unset($params['cols']['_token']);
            $res = $this->cathu->remoAll($params);


            if ($res > 0) {

                Session::flash('success , "Xóa thành công');
                return back();
            } else {
                Session::flash('error , "Xóa thành công');
                return back();
            }
        }
    }
}