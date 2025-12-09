<?php declare(strict_types = 1);

namespace App\Model\Database\Query;

use App\Model\Database\ORM\Addon\Addon;
use App\Model\Exceptions\Logical\InvalidStateException;
use Nextras\Dbal\QueryBuilder\QueryBuilder;

final class RssFeedQuery extends QueryObject
{

    // Types
    public const TYPE_LATEST = 1;
    public const TYPE_AUTHOR = 2;

    // Limits
    public const DEFAULT_LIMIT = 25;

    /**
     * @var int 
     */
    private $type;

    /**
     * @var string 
     */
    private $author;

    public function byLatest(): void
    {
        $this->type = self::TYPE_LATEST;
    }

    public function byAuthor(string $author): void
    {
        $this->type = self::TYPE_AUTHOR;
        $this->author = $author;
    }

    public function doQuery(QueryBuilder $builder): QueryBuilder
    {
        $qb = $builder->select('a.*')
            ->from('[addon]', 'a')
            ->andWhere('[a.state] = %s', Addon::STATE_ACTIVE);

        if ($this->type === self::TYPE_LATEST) {
            $qb->orderBy('[a.created_at] DESC');
        } elseif ($this->type === self::TYPE_AUTHOR) {
            $qb->andWhere('[a.author] = %s', $this->author);
        } else {
            throw new InvalidStateException('Unknown type');
        }

        if (!$this->limit) {
            $qb->limitBy(self::DEFAULT_LIMIT);
        }

        return $qb;
    }

}
