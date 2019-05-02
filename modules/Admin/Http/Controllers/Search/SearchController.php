<?php 

namespace Modules\Admin\Http\Controllers\Search;

use Input, Response, DB, Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\Appraisal\Order;
use Modules\Admin\Http\Controllers\AdminBaseController;

use Elasticsearch\ClientBuilder;
use ONGR\ElasticsearchDSL\Search;
use ONGR\ElasticsearchDSL\Query\{MultiMatchQuery, BoolQuery, WildcardQuery, MatchQuery};

class SearchController extends AdminBaseController {
  
  protected $type;
  protected $term;

  protected $client;
  protected $search;

  public function search(Request $request)
  {
    $this->type = $request->input('type');
    $this->term = $request->input('term');

    $this->client = ClientBuilder::create()->build();
    $this->search = new Search();
    switch($this->type) {
      case 'appraisal':
        return $this->searchAppraisals();
      case 'ticket':
        return $this->searchTickets();
    }

    return Response::json(['message' => 'Sorry, The type selected is not valid.'], 500);
  }

  public function searchTickets() {
    $multiMatchQuery = new MultiMatchQuery(
        ['title', 'id'],
        $this->term,
        ['type' => 'phrase']
    );
    $this->search->addQuery($multiMatchQuery, BoolQuery::SHOULD);

    $wildcard = new WildcardQuery('id', ('*' . $this->term));
    $this->search->addQuery($wildcard, BoolQuery::SHOULD);

    $results = $this->getSearch('ticket');

    $orders = collect($results['hits']['hits'])->map(function ($hit) {
      $order = $hit['_source'];
      $highlight = $hit['highlight'] ?? [];
      return [
          'link' => '#' . $this->get('id', $order, $highlight),
          'title' => sprintf('%s [%s]', $this->get('title', $order, $highlight), $this->get('id', $order, $highlight)),
          'description' => sprintf('%s', $order['closed_date'] ? ( Carbon::createFromTimestamp($order['closed_date'])->format('m/d/Y') . ' Closed' ) : ( $order['created_date'] ? (Carbon::createFromTimestamp($order['created_date'])->format('m/d/Y') . ' Created') : 'No Date' )),
      ];
    });

    return Response::json($orders);
  }

  public function searchAppraisals() {
    $multiMatchQuery = new MultiMatchQuery(
        ['address', 'id', 'loanrefnum', 'borrower'],
        $this->term,
        ['type' => 'phrase']
    );
    $this->search->addQuery($multiMatchQuery, BoolQuery::SHOULD);

    $wildcard = new WildcardQuery('id', ('*' . $this->term));
    $this->search->addQuery($wildcard, BoolQuery::SHOULD);

    $matchQuery = new MatchQuery('status', Order::STATUS_TEMP);
    $this->search->addQuery($matchQuery, BoolQuery::MUST_NOT);

    $results = $this->getSearch('appraisal');

    $orders = collect($results['hits']['hits'])->map(function ($hit) {
      $order = $hit['_source'];
      $highlight = $hit['highlight'] ?? [];
      return [
          'link' => '#' . $this->get('id', $order, $highlight),
          'title' => sprintf('%s [%s]', $this->get('address', $order, $highlight), $this->get('id', $order, $highlight)),
          'description' => sprintf('%s (%s)', $order['statusName'], $order['date_delivered'] ? ( Carbon::createFromFormat('Y-m-d H:i:s', $order['date_delivered'])->format('m/d/Y') . ' Delivered' ) : ( $order['date_ordered'] ? (Carbon::createFromFormat('Y-m-d H:i:s', $order['date_ordered'])->format('m/d/Y') . ' Ordered') : 'No Date' )),
      ];
    });
    dd(888);
    return Response::json($orders);
  }

  protected function getSearch($type) {
    $queryArray = $this->search->toArray();
    $sort = [
      'sort' => [
        [
          'id' => ['order' => 'desc'],
          '_score' => ['order' => 'desc'],
        ],
      ]
    ];
    $highlight = [
      'highlight' => [
        'pre_tags' => ['<em>'],
        'post_tags' => ['</em>'],
        'fields' => [
          '*' => ['number_of_fragments' => 0, 'fragment_size' => 150],
        ],
        'require_field_match' => true,
      ],
    ];
    $params = [
      'index' => 'landscape',
      'type' => $type,
      'body' => $queryArray + $sort + $highlight,
      'from' => 0,
      'size' => 100,
    ];

    return $this->client->search($params);
  }

  protected function get($key, $item, $highlight) {
    return isset($highlight[$key]) && isset($highlight[$key][0]) ?
      $highlight[$key][0] :
      $item[$key] ?? null;
  }
}