<?php

class RatesController extends Rest_Controller
{
    public function indexAction()
    {
        $rates = new Application_Model_RatesMapper();
        $this->getResponse()->setBody(json_encode($rates->fetchAll(), JSON_UNESCAPED_UNICODE)); //, JSON_UNESCAPED_UNICODE
        $this->getResponse()->setHttpResponseCode(200);
    }

    public function getAction()
    {
        $rates = new Application_Model_RatesMapper();
        $this->getResponse()->setBody(
            json_encode(
                $rates->find($this->getParam('id')),
                JSON_UNESCAPED_UNICODE
            )
        );
        $this->getResponse()->setHttpResponseCode(200);
    }

    public function postAction()
    {
        $this->getResponse()->setHttpResponseCode(405);
    }

    public function putAction()
    {
        $this->getResponse()->setHttpResponseCode(405);
    }

    public function deleteAction()
    {
        $this->getResponse()->setHttpResponseCode(405);
    }

    /**
     * обновляем значения валют и возвращаем обновлённый список
     */
    public function refreshAction()
    {
        $rates = new Application_Model_RatesMapper();
        $rates->refresh();
        $this->getResponse()->setBody(
            json_encode($rates->fetchAll(), JSON_UNESCAPED_UNICODE)
        );
        $this->getResponse()->setHttpResponseCode(200);
    }

    /**
     * устанавливаем статус видимости валюты
     */
    public function toggleAction()
    {
        // запрос может быть либо PUT либо OPTIONS, который предваряет PUT
        if (!$this->getRequest()->isPut()) {
            if (!$this->getRequest()->isOptions()) {
                $this->getResponse()->setHttpResponseCode(405);
            }
            return;
        }

        $requestBody = [];
        parse_str($this->getRequest()->getRawBody(), $requestBody);

        $rates = new Application_Model_RatesMapper();

        // пытаемся обновить значение у валюты
        try {
            $rates->toggleAppearance(
                $this->getParam('id'),
                filter_var($requestBody['data'], FILTER_VALIDATE_BOOLEAN)
            );
        } catch (Exception $exception) {
            $this->getResponse()->setBody(
                json_encode(['error' => 'Failed to update data.'], JSON_UNESCAPED_UNICODE)
            );
            $this->getResponse()->setHttpResponseCode(500);
        }

        // если всё ок, возвращаем статус success
        $this->getResponse()->setBody(
            json_encode(['success' => true], JSON_UNESCAPED_UNICODE)
        );
        $this->getResponse()->setHttpResponseCode(200);
    }
}

