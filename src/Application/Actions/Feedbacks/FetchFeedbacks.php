<?php

namespace App\Application\Actions\Feedbacks;

use Exception;
use Slim\Exception\HttpInternalServerErrorException;
use Respect\Validation\Validator as v;
use App\Requests\CustomRequestHandler;
use App\Application\Actions\Feedbacks\FeedbacksAction;

class FetchFeedbacks extends FeedbacksAction
{
    public function action()
    {
        try {
            
            $query = $this->request->getQueryParams();
            
            $page = array_key_exists('page', $query) && $query['page'] ?  $query['page'] : 1;
            $limit = array_key_exists('limit', $query) && $query['limit'] ?  $query['limit'] : 10;
            $feedbacks = $this->fbServices->fetchFeebacks($limit, $page);

            // get page
            $total = $feedbacks['total'];
            $maxPage = ceil($total / $limit);
            $hasPrev = $page == 1 || $page - 1 > $maxPage ? false : true;
            $hasNext = $page >= $maxPage ? false : true;
            $feedbacks['pages'] = ['total' => $maxPage, 'current' => (int)$page, 'prev' => $page - 1, 'next' => $page + 1, 'hasPrev' => $hasPrev, 'hasNext' => $hasNext];


            return $this->respondWithData($feedbacks);
        } catch (Exception $e) {
            throw new HttpInternalServerErrorException($this->request, $e->getMessage());
        }
    }
}
