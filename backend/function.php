<?php

function load_user($username) {
    require 'db.php';

    $stmt = $link->prepare("SELECT * FROM account WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();

    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    $stmt->close();
    $link->close();

    return $user ?: null;
}

function select_family_information($username) {
    require 'db.php';

    // 先取得 account id
    $stmt = $link->prepare("SELECT id FROM account WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    $account = $result->fetch_assoc();
    $stmt->close();

    if (!$account) {
        $link->close();
        return [];
    }

    $account_id = $account['id'];

    // 再取得該使用者的親人資料
    $stmt = $link->prepare("SELECT * FROM relative_information WHERE account_id = ?");
    $stmt->bind_param("i", $account_id);
    $stmt->execute();
    $result = $stmt->get_result();

    $families = [];

    while ($row = $result->fetch_assoc()) {
        $state = ($row['video_state'] === 'YES') ? '已準備好' : '未準備好';

        $families[] = [
            'id' => $row['id'],
            'account_id' => $row['account_id'],
            'relative_name' => $row['relative_name'],
            'relative_relation' => $row['relative_relation'],
            'picture_name' => $row['picture_name'],
            'relative_picture_path' => $row['relative_picture_path'],
            'relative_video_path' => $row['relative_video_path'],
            'video_state' => $state
        ];
    }

    $stmt->close();
    $link->close();

    return $families;
}

function load_family($family_id) {
    require 'db.php';

    $stmt = $link->prepare("SELECT * FROM relative_information WHERE id = ?");
    $stmt->bind_param("i", $family_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    $stmt->close();
    $link->close();

    if (!$row) {
        return null;
    }

    $state = ($row['video_state'] === 'YES') ? '已準備好' : '未準備好';

    return [
        'id' => $row['id'],
        'account_id' => $row['account_id'],
        'relative_name' => $row['relative_name'],
        'relative_relation' => $row['relative_relation'],
        'picture_name' => $row['picture_name'],
        'relative_picture_path' => $row['relative_picture_path'],
        'relative_video_path' => $row['relative_video_path'],
        'video_state' => $state
    ];
}

?>