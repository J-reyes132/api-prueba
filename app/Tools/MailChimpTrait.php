<?php


namespace App\Tools;

use App\Exceptions\SomethingWentWrong;
use App\Models\Profile;
use Illuminate\Support\Facades\DB;

trait MailChimpTrait
{

public function createContact(Profile $perfil)
{
    if (ENV('MAIL_CHIMP_ENABLE')) {
        // $mailchimp = new \MailchimpMarketing\ApiClient();
        $mailchimp = new \MailchimpMarketing\ApiClient();

        $mailchimp->setConfig([
            'apiKey' => ENV('MAIL_CHIMP_API_KEY'),
            'server' => ENV('MAIL_CHIMP_SERVER')
        ]);

        $list_id = ENV('MAIL_CHIMP_AUDIENCE_ID');

        try {
            $response = $mailchimp->lists->setListMember($list_id, $perfil->usuario->email , [
                "email_address" => $perfil->usuario->email,
                "status_if_new" => "unsubscribed",
            ]);

            $response = $mailchimp->lists->updateListMember($list_id, $perfil->usuario->email, [
                "email_address" => $perfil->usuario->email,
                "status" => "subscribed",
                "merge_fields" => [
                    "FNAME" => $perfil->nombres,
                    "LNAME" => $perfil->apellidos
                ]
            ]);

            // print_r($response);
        } catch (\Throwable $th) {
            throw new SomethingWentWrong($th);
        }
    }
}

public function tagContact(Profile $perfil)
{
    if (ENV('MAIL_CHIMP_ENABLE')) {
        $mailchimp = new \MailchimpMarketing\ApiClient();

        $mailchimp->setConfig([
            'apiKey' => ENV('MAIL_CHIMP_API_KEY'),
            'server' => ENV('MAIL_CHIMP_SERVER')
        ]);

        $list_id = ENV('MAIL_CHIMP_AUDIENCE_ID');

        $subscriberHash = md5(strtolower($perfil->usuario->email));

        try {
            $mailchimp->lists->updateListMemberTags($list_id, $subscriberHash, [
                "tags" => [
                    [
                        "name" => ENV('MAIL_CHIMP_TAG'),
                        "status" => "active"
                    ]
                ]
            ]);
        } catch (\Throwable $th) {
            throw new SomethingWentWrong($th);
        }
    }
}
}
