<?php

$example_persons_array = [
    [
        'fullname' => 'Иванов Иван Иванович',
        'job' => 'tester',
    ],
    [
        'fullname' => 'Степанова Наталья Степановна',
        'job' => 'frontend-developer',
    ],
    [
        'fullname' => 'Пащенко Владимир Александрович',
        'job' => 'analyst',
    ],
    [
        'fullname' => 'Громов Александр Иванович',
        'job' => 'fullstack-developer',
    ],
    [
        'fullname' => 'Славин Семён Сергеевич',
        'job' => 'analyst',
    ],
    [
        'fullname' => 'Цой Владимир Антонович',
        'job' => 'frontend-developer',
    ],
    [
        'fullname' => 'Быстрая Юлия Сергеевна',
        'job' => 'PR-manager',
    ],
    [
        'fullname' => 'Шматко Антонина Сергеевна',
        'job' => 'HR-manager',
    ],
    [
        'fullname' => 'аль-Хорезми Мухаммад ибн-Муса',
        'job' => 'analyst',
    ],
    [
        'fullname' => 'Бардо Жаклин Фёдоровна',
        'job' => 'android-developer',
    ],
    [
        'fullname' => 'Шварцнегер Арнольд Густавович',
        'job' => 'babysitter',
    ],
];


	/**
	 * mb_ucfirst - преобразует первый символ в верхний регистр
	 * @param string $str - строка
	 * @param string $encoding - кодировка, по-умолчанию UTF-8
	 * @return string
	 */
	function mb_ucfirst($str, $encoding='UTF-8')
	{
		$str = mb_ereg_replace('^[\ ]+', '', $str);
		$str = mb_strtoupper(mb_substr($str, 0, 1, $encoding), $encoding).
			   mb_substr($str, 1, mb_strlen($str), $encoding);
		return $str;
	}

function getPartsFromFullname($str) {
    $arr = explode(" ", $str);
    return array (
        'surname' => $arr[0] ,
        'name' => $arr[1] , 
        'patronomyc' => $arr[2]
    );
}

function getFullnameFromParts($arr) {
    return implode(' ',$arr); 
}

function getShortName($str){
    $parts = getPartsFromFullname($str);
    return $parts['name'].' '.mb_substr($parts['surname'], 0, 1).'.';
}

function getGenderFromName($str){
    $parts = getPartsFromFullname($str);
    $gender = 0;
    if (mb_substr($parts['patronomyc'], -3) == 'вна'){
        $gender--;
    }elseif (mb_substr($parts['patronomyc'], -2) == 'ич'){
        $gender++;
    }
    if (mb_substr($parts['name'], -1) == 'а'){
        $gender--;
    }elseif (mb_substr($parts['name'], -1) == 'й' || mb_substr($parts['name'], -1) == 'н'){
        $gender++;
    }
    if (mb_substr($parts['surname'], -2) == 'ва'){
        $gender--;
    }elseif (mb_substr($parts['surname'], -1) == 'в'){
        $gender++;
    }
    if ($gender > 1){
        $gender = 1;
    }elseif ($gender < 0){
        $gender = -1;
    }
    return $gender;
}

function getGenderDescription($person_arr){
    $names = array();
    for ($i = 0; $i < count($person_arr); $i++){
        $names[$i] = $person_arr[$i]['fullname']; 
    }
    $males = array_filter($names, function ($name) {
        return getGenderFromName($name) == 1;
    });
    $females = array_filter($names, function ($name) {
        return getGenderFromName($name) == -1;
    });
    $unknown = array_filter($names, function ($name) {
        return getGenderFromName($name) == 0;
    });
    $males_pct = round(count($males)/count($names)*100, 1);
    $females_pct = round(count($females)/count($names)*100, 1);
    $unknown_pct = round(count($unknown)/count($names)*100, 1);
    echo "Гендерный состав аудитории: \n
--------------------------- \n
Мужчины - ${males_pct}% \n
Женщины - ${females_pct}% \n
Не удалось определить - ${unknown_pct}%";
}

function getPerfectPartner($surname,$name,$patronomyc,$person_arr){
    $parts = array (
        'surname' => mb_ucfirst(mb_strtolower($surname)) ,
        'name' => mb_ucfirst(mb_strtolower($name)) , 
        'patronomyc' => mb_ucfirst(mb_strtolower($patronomyc))
    );
    $fullname = getFullnameFromParts($parts);
    $gender = getGenderFromName($fullname);
    if ($gender == 0){
        echo "Не удалось определить пол: ${fullname}";
        return; 
    }
    $names = array();
    for ($i = 0; $i < count($person_arr); $i++){
        $names[$i] = $person_arr[$i]['fullname']; 
    }
    $random_person = null;
    $found = false;
    while($found == false){
        $random_person = $names[array_rand($names)];
        $random_person_gender = getGenderFromName($random_person);
        
        if($random_person_gender != $gender && $random_person_gender != 0){
            $short_name = getShortName($fullname);
            $short_partner = getShortName($random_person);
            $random_match = round(rand(5000,10000)/100, 2);
            echo "${short_name} + ${short_partner} = \n♡ Идеально на ${random_match} ♡";
            $found = true;
        };
    }

}