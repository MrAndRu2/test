<?php

class Test 
{

    /**
     * 
     */
    public function test()
    {
        /**
         * проверка на приходящие данные
         * Как правило все должно быть хорошо, но лучше проверить, особенно если ввод проиходит вручную
         * @param string id пользователей,  формата "1,2,17,48"
         * @return string проверенные и только числовые id пользователей,  формата "1,2,17,48"
         */
        function validate (string $string_users_id) : string
        { 
            $array_users_id = explode(',', $string_users_id);
            $verified_ids = [];
            foreach($array_users_id as $user_id){
                if(is_int((int) ($user_id)) && $user_id > 0){
                    $verified_ids[] = $user_id;
                }
            }
            return implode(',', $verified_ids);
        }

        /**
         * запрос для данных пользователя
         * 
         * @param string id пользователей,  формата "1,2,17,48"
         * @return array массив пользователей формата user_id => user_name
         */
        function load_users_data (string $user_ids) : array
        {
            if(!$user_ids){
                return [];
            }
            $db = mysqli_connect("localhost", "root", "123123", "database");
            $sql = mysqli_query($db, "SELECT id, name FROM users WHERE id IN ($user_ids)");
            $data = [];
            while($obj = $sql->fetch_object()){
                $data[$obj->id] = $obj->name;
            }
            mysqli_close($db);
            return $data;
        }

        /**если данные переданы совсем не так, ничего не делаем */
        if(!is_string($_GET['user_ids'])){
           return; 
        }
        $verified_ids = validate($_GET['user_ids']);
        $data = load_users_data($verified_ids);
        foreach ($data as $user_id=>$name) {
            /** таблица создана так, что имени может и не быть*/
            if(!$name){
                $name = "Неопознанный субъект №$user_id";
            }
            echo "<a href=\"/show_user.php?id=$user_id\">$name</a>";
        }
    }
}