<?php
namespace base;

class Util {


  // 한국인 /////////////////////////////////////////////////////////////

  public function getAge($birthday) {
    return (int) date('Y') - substr($birthday, 0, 4) + 1;
  }


  // 어레이 ////////////////////////////////////////////////////////////

  // 숫자키 어레이에 특정 값으로 키를 할당한다.
  public function assignArrayKeys($array, $keyname, $needMove = false) {
    $result = [];
    foreach($array as $item) {

      $key = $item[$keyname];

      if ($needMove) unset($item[$keyname]);

      $result[$key] = $item;
    }
    return $result;
  }

  public function array_merge_recursive_distinct(&$array1, &$array2) {
    $merged = $array1;
    foreach ( $array2 as $key => &$value ) {
      if ( is_array ( $value ) && isset ( $merged [$key] ) && is_array ( $merged [$key] ) ) {
        $merged [$key] = $this->array_merge_recursive_distinct ( $merged [$key], $value );
      }
      else {
        $merged [$key] = $value;
      }
    }
    return $merged;
  }


  // 쿼리 ///////////////////////////////////////////////////////////////

  // 날짜구간을 어레이로 리턴
  public function getDates($from='2016-07-01', $to='') {
    if (!$to) $to = date('Y-m-d', strtotime('-1 day'));
    $dates = [];
    $i = 0;
    while( ( $date = date('Y-m-d', strtotime($from.'+'.$i.' days')) ) <= $to ) {
      $dates[] = $date;
      $i++;
    }
    return $dates;
  }


  // 회사 정보 ////////////////////////////////////////////////////////////

  // 업무일 구하기
  public function getWorkingDay($date) {

    $양력 = [
      '10-03', // 개천절
      '10-09', // 한글날
      '12-25', // 성탄절
      '01-01', // 새해 첫널
      '03-01', // 삼일절
      '05-05', // 어린이날
      '06-06', // 현충일
      '08-15', // 광복절
    ];

    $음력 = [
      '2017-01-27', // 설날
      '2017-01-28', // 설날
      '2017-01-29', // 설날
      '2017-01-30', // 설날
      '2017-05-03', // 석가탄신일
      '2017-10-04', // 추석
      '2017-10-05', // 추석
      '2017-10-06', // 추석
      '2017-12-20', // 19대 대선
    ];

    while(
      in_array( date('D', strtotime($date)), ['Sat','Sun'] )
      || in_array(substr($date, -5), $양력)
      || in_array($date, $음력)
    ) {
      $date = date('Y-m-d', strtotime($date.' +1 day'));
    }

    return $date;
  }


  // 출력포앳 /////////////////////////////////////////////////////////////

  // 전화번호 포맷
  public function formatPhone($str) {
    if (strpos($str, '-')) return $str;
    $phone[0] = substr($str, 0, 3);
    $phone[1] = strlen($str)==11 ? substr($str, 3, 4) : substr($str, 3, 3);
    $phone[2] = substr($str, -4);
    return implode('-', $phone);
  }

  // CSV로 변환
  public function toCsv($list, $delimiter=';') {

    if (!$list) return '';

    $csv = '';

    $keys = array_keys($list);
    $csv .= $delimiter . implode($delimiter, array_keys($list[$keys[0]])) . "\n";

    foreach($list as $key=>$item) {
      /*foreach($item as &$values) {
        if ($values[0]==='0') $values = '`' . $values;
      }*/
      $csv .= $key . $delimiter . implode($delimiter, $item) . "\n";
    }
    return $csv;
  }

  // 어레이를 html 테이블로 변환 (디버그용)
  public function arrayToTable($data) {

    $table = '';
    $thead = '';
    $tbody = '';

    if (!$data) return '';

    foreach($data as $key => $row) {

      if (!$thead) {
        $thead .= '<tr><th></th>';
        foreach($row as $th=>$datum) {
          $thead .= '<th>'.$th.'</th>';
        }
        $thead .= '</tr>';
      }

      $tbody .= '<tr><th>'.$key.'</th>';
      foreach($row as $datum) {
        $tbody .= '<td>'.$datum.'</td>';
      }
      $tbody .= '</tr>';
    }

    $table = '<table border=1 cellpadding=5>' . $thead . $tbody . '</table>';

    return $table;
  }




}
