<?php

declare(strict_types=1);

namespace Inpsyde\UserListTable;

// -*- coding: utf-8 -*-

class UserListTable
{
    /**
     * @var webApi
     */
    protected $webApi = 'https://jsonplaceholder.typicode.com/users';
    /**
     * Get API Call
     * @return JSON
     */
    public function getUserListAPICall()
    {
        $response = wp_remote_get($this->webApi);
        $userResponse = wp_remote_retrieve_body($response);
        $userList = json_decode($userResponse);

        return $userList;
    }
    /**
     * Get List of Users
     * @return text
     */
    public function getUserList()
    {

        try {
            $userList = $this->getUserListAPICall();
            $userHtml = '<table width="100%" cellpadding="0" cellspacing="0">';
            $userHtml .= '<tr><td><b>ID</b></td><td><b>Name</b></td><td><b>Username</b></td></tr>';
            foreach ($userList as $users) {
                $userHtml .= '<tr>';
                $userHtml .= '<td><a class="listofUsers" 
                data-id="' . $users->id . '">' . $users->id . '</a></td>';
                $userHtml .= '<td><a class="listofUsers" 
                data-id="' . $users->id . '">' . $users->name . '</a></td>';
                $userHtml .= '<td><a class="listofUsers" 
                data-id="' . $users->id . '">' . $users->username . '</a></td>';
                $userHtml .= '</tr>';
            }
            $userHtml .= '</table>';
            $userHtml .= '<table width="100%" cellpadding="2" cellspacing="2">';
            $userHtml .= '<tr><td><div class="userDetailSection"></div></td></tr>';
            $userHtml .= '</table>';

            return $userHtml;
        } catch (\Exception $ex) {
            return $ex->getMessage();
        }
    }
}
$users = new userListTable();
echo $users->getUserList();
