<?php

namespace Hanslife\Date;

class CheckDay
{
    const DAY_LIST = [
        2023 => [
            'holiday'      => ['2023-01-01', '2023-01-02', '2023-01-21', '2023-01-22', '2023-01-23', '2023-01-24', '2023-01-25', '2023-01-26', '2023-01-27', '2023-04-05', '2023-04-29', '2023-04-30', '2023-05-01', '2023-05-02', '2023-05-03', '2023-06-22', '2023-06-23', '2023-06-24', '2023-09-29', '2023-09-30', '2023-10-01', '2023-10-02', '2023-10-03', '2023-10-04', '2023-10-05', '2023-10-06'],
            'exchange_day' => ['2023-01-28', '2023-01-29', '2023-04-23', '2023-05-06', '2023-06-25', '2023-10-07', '2023-10-08']
        ]
    ];

    /**
     * 获取日期信息
     * @param $today_date
     * @return array|mixed
     */
    public function getDate($today_date = '')
    {
        if (!$today_date) {
            $today_date = date('Y-m-d');
            $year       = date('Y');
        } else {
            $year  = date('Y', strtotime($today_date));
        }

        if (!isset(self::DAY_LIST[$year])) {
            $day_info = [];
        } else {
            $list = $this->makeWorkDay(self::DAY_LIST[$year]['holiday'], self::DAY_LIST[$year]['exchange_day']);
            $day_info = $list[$today_date];
        }
        return $day_info;
    }

    /**
     * 生成工作日
     * @param array $holiday 法定节假日
     * @param array $exchange_day 调休日
     * @return array  $calendar
     *        day：日期 ，2023-01-01
     *        day_type：类型 ，0-工作日，1-法定节假日，2-周末加班调休，3-周末
     *        is_work_day：是否为工作日，1-是，0-否
     */
    public function makeWorkDay($holiday, $exchange_day)
    {
        $calendar = [];
        $year     = 0;

        //法定节假日
        foreach ($holiday as $k => $v) {
            $day            = date('Y-m-d', strtotime($v));
            $calendar[$day] = [
                'day'         => $day,
                'day_type'    => 1,
                'is_work_day' => 0
            ];

            if (!$year) {
                $year = date('Y', strtotime($day));
            }
        }

        //调休日
        foreach ($exchange_day as $k => $v) {
            $day            = date('Y-m-d', strtotime($v));
            $calendar[$day] = [
                'day'         => $day,
                'day_type'    => 2,
                'is_work_day' => 1
            ];
        }

        //其他日期
        $this_date = $year . '-01-01';
        $end_date  = ($year + 1) . '-01-01';
        while (strtotime($this_date) < strtotime($end_date)) {
            if (!isset($calendar[$this_date])) {
                $w = date('w', strtotime($this_date));
                if (in_array($w, [0, 6])) {
                    $calendar[$this_date] = [
                        'day'         => $this_date,
                        'day_type'    => 3,
                        'is_work_day' => 0
                    ];
                } else {
                    $calendar[$this_date] = [
                        'day'         => $this_date,
                        'day_type'    => 0,
                        'is_work_day' => 1
                    ];
                }
            }
            $this_date = date('Y-m-d', strtotime($this_date . ' +1 day'));

        }

        ksort($calendar);

        return $calendar;

    }
}