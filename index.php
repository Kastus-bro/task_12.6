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


// Разделение и объединение ФИО
function getFullnameFromParts($surname, $name, $patronomyc) {
    return $surname .' '. $name .' '. $patronomyc;
};
print_r (getFullnameFromParts('Иванов','Иван','Иванович'));

function getPartsFromFullname($fullName) {
    $newArray = explode(' ', $fullName);
    $indexArray = ['surname', 'name', 'patronomyc'];
    return array_combine($indexArray, $newArray);
};
print_r (getPartsFromFullname('Иванов Иван Иванович'));



// Сокращение ФИО
function getShortName($fullName) {
    $shortName = getPartsFromFullname($fullName);
    return $shortName['name'] .' '. mb_substr($shortName['patronomyc'], 0, 1) .'.';       
};
print_r (getShortName('Иванов Иван Иванович'));


// Определение пола по ФИО
function getGenderFromName($fullName)
{
    $name = getPartsFromFullname($fullName);
    $genderCounter = 0;
    
    if (mb_substr($name['patronomyc'],-3,3,'UTF-8') === 'вна')
        {
            $genderCounter -=1;
        }
    if (mb_substr($name['name'], -1, 1) === 'а') 
        {
            $genderCounter -= 1;
        }
    if (mb_substr($name['surname'], -2, 2) === 'ва') 
        {    
            $genderCounter -= 1;
        }                
    if (mb_substr($name['patronomyc'],-2,2,'UTF-8') === 'ич') 
        {
            $genderCounter += 1;
        }
    if (mb_substr($name['name'], -1, 1) === ('й'||'н')) 
        {
            $genderCounter += 1;        
        }
    if (mb_substr($name['surname'], -1, 1) === 'в') 
        {
            $genderCounter += 1;
        }        

    if ($genderCounter > 0)
    {
        return 'Мужской пол';
    }
    if ($genderCounter < 0)
    {
        return 'Женский пол';
    }
    if ($genderCounter === 0)
    {
        return 'Неопределённый пол';
    }

};
print_r (getGenderFromName('аль-Хорезми Мухаммад ибн-Муса'));


//Определение возрастно-полового состава
function getGenderDescription($arr)
{
    foreach ($arr as $key) {
        $genderArr[] = getGenderFromName ($key['fullname']);
    }

    $menCount = count(array_filter($genderArr,function ($key) {
        return $key == 'Мужской пол';
    }));
    $womenCount = count(array_filter($genderArr,function ($key) {
        return $key == 'Женский пол';
    }));
    $unknownCount = count(array_filter($genderArr,function ($key) {
        return $key == 'Неопределённый пол';
    }));

    $menPercent = round($menCount / count($arr) * 100, 1);
    $womenPercent = round($womenCount / count($arr) * 100, 1);
    $unknownPercent = round($unknownCount / count($arr) * 100, 1);;
   
    return [$menPercent,$womenPercent,$unknownPercent];    
};

$result = getGenderDescription($example_persons_array);
echo 'Гендерный состав аудитории:<br/>';
echo '-------------------------------------<br/>';
echo 'Мужчины -' .' '. $result[0] .' '. '%<br/>';
echo 'Женщины -' .' '. $result[1] .' '. '%<br/>';
echo 'Не удалось определить -' .' '. $result[2] .' '. '%<br/>';


// Идеальный подбор пары
function getPerfectPartner ($surname,$name,$patronomyc,$arr)
{
    $newFullName = getFullnameFromParts (
        mb_convert_case($surname, MB_CASE_TITLE_SIMPLE),
        mb_convert_case($name, MB_CASE_TITLE_SIMPLE),
        mb_convert_case($patronomyc, MB_CASE_TITLE_SIMPLE));

    $newGender = getGenderFromName($newFullName); 
    do {
        $randomPerson = $arr[array_rand($arr, 1)]['fullname'];
    }
    while ($newGender == getGenderFromName($randomPerson));  

    $probability = round(rand(5000,10000)/100,2);

    return getShortName($newFullName) .' + '. getShortName($randomPerson) .' =<br/>'
    . '&#9829;'.' Идеально на '. $probability .'%'. ' &#9829;'; 
};

print_r (getPerfectPartner('борзич','константин','игоревич',$example_persons_array));

?>