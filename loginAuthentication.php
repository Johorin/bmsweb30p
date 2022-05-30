<?php
/*
 * 関数名：authenticate
 * 引数：なし
 * 戻り値：ユーザーIDと具体的な権限名の連想配列$authInfo
 */
function authenticate() {
    //セッションに登録されているユーザー情報を取得
    $userInfo = $_SESSION['userInfo'];

    if(!isset($userInfo)) { //セッションに登録されているユーザー情報が無い場合実行
        //ログイン画面に遷移
        header('Location: ./login.php');
        exit();
    } else {
        //ユーザー情報
        switch ($userInfo['authority']) {
            case '1':
                $authority = '一般ユーザ';
                break;
            case '2':
                $authority  = '管理者';
        }

        //ログイン中のユーザー名と権限を連想配列に格納
        $authInfo = [
            'userName' => $userInfo['user'],
            'authority' => $authority,
        ];

        return $authInfo;
    }
}
?>