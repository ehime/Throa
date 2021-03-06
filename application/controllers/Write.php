<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

Class Write Extends CI_Controller //Implements WriteInterface
{

    private $filter = [];

    public function index()
    {
        $this->filter();

        $this->load->helper('url');
        redirect('/', 'refresh');
    }

    private function filter()
    {
        if (isset($_POST) && ! empty($_POST))
        {
            $filter = filter_input_array(INPUT_POST, [
                'uid' => FILTER_SANITIZE_ENCODED,
                'aid' => FILTER_SANITIZE_ENCODED,
                'loc' => [
                    'filter' => FILTER_VALIDATE_REGEXP,
                    'options' => [
                        'regexp' => '/^(instagram|twitter)$/i'
                    ],
                ],
            ]);


            foreach (array_keys($filter) AS $key)
            {
                // check for empty based off of filters array keys
                if (! preg_match('/\d/', trim($filter[$key])))
                {
                    $this->filter[$key] = trim(strtolower($filter[$key]));
                }
            }

            if ((3 === count($this->filter)) && serialize(array_keys($filter)) === serialize(array_keys($this->filter)))
                $this->addToQueue();

            unset($this->filter); //oikology
        }
        return $this;
    }

    private function addToQueue()
    {
        if (! empty($this->filter) && (3 === count($this->filter)))
        {
            $this->load->model('Adapter');

            if (true === Adapter::configs())      // db exists and can talk to it
            {
                Adapter::addBulkQueue($this->filter);

                die('hit');
            }
        }

        die('miss');
        return $this;
    }
}

/* End of file Write.php */
/* Location: ./application/controllers/Write.php */