<?php
namespace spec\Netsensia\Pinnacle\Api\Client;

include "spec/SpecHelper.php";

use PhpSpec\ObjectBehavior;

class ClientSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Netsensia\Pinnacle\Api\Client\Client');
    }
    
    function it_can_get_sports()
    {
        $this->beConstructedWith(getApiKey());
        
        $this->getSports()->sports[0]->name->shouldBe('Badminton');
    }

    public function getMatchers()
    {
        return [
            'containCompany' => function ($subject, $name) {
                foreach ($subject as $company) {
                    if ($company->title == $name) {
                        return true;
                    }
                }
                return false;
            }
        ];
    }
}
