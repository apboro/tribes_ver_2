<?php

namespace App\Filters\API;

use App\Filters\QueryFilter;
use App\Helper\ArrayHelper;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Http\Request;

/**
 * @property EloquentBuilder $builder
 * @method EloquentBuilder apply(EloquentBuilder $builder)
 */
class QuestionsFilter extends QueryFilter
{
    const QUESTION_ALL = 'all';
    const QUESTION_HAS_ANSWER = 'with_answer';
    const QUESTION_WITHOUT_ANSWER = 'no_answer';
    const QUESTION_IS_PUBLIC = 'public';
    const QUESTION_NOT_PUBLIC = 'not_public';
    const QUESTION_IS_DRAFT = 'draft';
    const QUESTION_NOT_DRAFT = 'not_draft';

    const SORT_DESC = 'desc';
    const SORT_ASC = 'asc';
    const SORT_DEFAULT = 'default';

    protected function _sortingName($name): string
    {
        $list = [
            'id' => 'id',
            'update_date' => 'updated_at',
            'enquiry' => 'c_enquiry',
            'default' => 'id',
        ];
        return $list[$name] ?? $list['default'];
    }

    protected function _sortingRule($rule): string
    {
        $list = [
            self::SORT_DESC => 'desc',
            self::SORT_ASC => 'asc',
            self::SORT_DEFAULT => 'desc',
        ];
        return $list[$rule] ?? $list[self::SORT_DEFAULT];
    }

    public function __construct(Request $request)
    {
        parent::__construct($request);
    }

    public function filters() : array
    {
        //todo сделать валидацию входящих данных, enum
        //     фильтры проверять на правильность значений и выбрасывать Exception
        //      null значения считается что такой фильтр не запрашивался \App\Filters\QueryFilter::apply()
        $filters = $this->request->get('filter', []);
        $filters['sort'] = $filters['sort']??['name'=>'id'];

        return $filters;
    }

    /**
     * @return Request
     */
    public function getRequest(): Request
    {
        return $this->request;
    }

    /**
     * @note зарезервировано под пагинацию используется в репозитории
     * \App\Repositories\Knowledge\KnowledgeRepository::getQuestionsByCommunityId()
     * request('filter.per_page',15)
     * request('filter.page',1)
     * @return EloquentBuilder
     */
    public function page()
    {
        return $this->builder;
    }

    //зарезервировано под пагинацию
    public function perPage()
    {
        return $this->builder;
    }

    public function sort(array $data)
    {
        $name = ArrayHelper::getValue($data, 'name', 'id');
        $name = $this->_sortingName(strtolower($name));
        $rule = ArrayHelper::getValue($data, 'rule', self::SORT_DESC);
        $rule = $this->_sortingRule(strtolower($rule));
        $this->builder->orderBy($name,$rule);
    }

    /**
     * filter[with_answers]
     *
     * @param int $value enumerate ALL,QUESTION_HAS_ANSWER,QUESTION_WITHOUT_ANSWER
     * @return EloquentBuilder
     */
    public function withAnswers($value): EloquentBuilder
    {
        $value = (string)$value;
        if ($value === self::QUESTION_HAS_ANSWER) {
            $this->builder->whereHas('answer');
        } elseif ($value === self::QUESTION_WITHOUT_ANSWER) {
            $this->builder->doesntHave('answer');
        }

        return $this->builder;
    }

    /**
     * filter[enquiry_more_then]
     * @param int $value
     * @return EloquentBuilder
     */
    public function enquiryMoreThen($value): EloquentBuilder
    {
        $value = (int)$value;
        return $this->builder->where('c_enquiry', '>', $value);
    }

    /**
     * filter[draft]
     * @param string $value  enum QUESTION_IS_DRAFT QUESTION_NOT_DRAFT
     * @return EloquentBuilder
     */
    public function draft($value): EloquentBuilder
    {
        $value = (string)$value;
        if ($value === self::QUESTION_IS_DRAFT) {
            $this->builder->where(['is_draft' => true]);
        } elseif ($value === self::QUESTION_NOT_DRAFT) {
            $this->builder->where(['is_draft' => false]);
        }
        return $this->builder;
    }

    /**
     * filter[published]
     * @param string $value enumerate QUESTION_IS_PUBLIC,QUESTION_NOT_PUBLIC
     * @return EloquentBuilder
     */
    public function published($value): EloquentBuilder
    {
        $value = (string)$value;
        if ($value === self::QUESTION_IS_PUBLIC) {
            $this->builder->where(['is_public' => true]);
        } elseif ($value === self::QUESTION_NOT_PUBLIC) {
            $this->builder->where(['is_public' => false]);
        }
        return $this->builder;
    }

    public function status($value): EloquentBuilder
    {
        $value = (string)$value;
        if ($value === self::QUESTION_NOT_PUBLIC) {
            $this->builder->where(['is_public' => false, 'is_draft' => false]);
        } elseif ($value === self::QUESTION_IS_PUBLIC) {
            $this->builder->where(['is_public' => true]);
        } elseif ($value === self::QUESTION_IS_DRAFT) {
            $this->builder->where(['is_public' => false, 'is_draft' => true]);
        }

        return $this->builder;
    }

    /**
     * filter[full_text]
     * @param string $value
     * @return EloquentBuilder
     */
    public function fullText($value): EloquentBuilder
    {
        $value = trim((string)$value);

        return $this->builder->where('context', 'LIKE', "%{$value}%")
            /*->orWhereHas('answer', function (EloquentBuilder $query) use ($value) {
                return $query->where('context', 'LIKE', "%{$value}%");
            })*/ ;
    }
}