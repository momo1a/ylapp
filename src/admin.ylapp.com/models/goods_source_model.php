<?php

/**
 * 商品来源
 *
 */
class Goods_source_model extends Zhs_goods_source_model
{
	// 使用从库
	protected $db_con = 'slave';
	
	/**
	 * 获取商品来源数据
	 * @param int $offset
	 * @param int $size
	 *
	 * @author 杜嘉杰
	 * @version 2015年10月16日  下午2:03:59
	 *
	 */
	public function sources_stat($offset,$size,$start_time,$end_time)
	{
		$dbprefix = $this->db->dbprefix;
		$goods_table = $dbprefix . Zhs_goods_model::$table_name;
		$query = "
			SELECT g.source,g.source_count,s.input_name,s.state FROM(
			SELECT source,count(*) AS 'source_count'
			FROM {$goods_table} 
			WHERE state>=20
			AND dateline BETWEEN {$start_time} AND {$end_time}
			GROUP BY source HAVING COUNT(*)>0
		) g
		LEFT JOIN {$dbprefix}{$this::$table_name} AS s ON (g.source=s.id)
		ORDER BY s.sort DESC, s.id ASC
		LIMIT {$offset},{$size}; ";
		
		$data = $this->db->query($query)->result_array();

		return $data;
	}
	
	/**
	 * 获取商品来源统计的总数
	 * 
	 * @author 杜嘉杰
	 * @version 2015年10月16日  下午2:03:39
	 *
	 */
	public function sources_stat_count($start_time,$end_time)
	{
		$dbprefix = $this->db->dbprefix;
		$goods_table = $dbprefix . Zhs_goods_model::$table_name;
		$query = "
		SELECT count(*) as 'count' FROM(
		SELECT source,count(*) AS 'source_count'
		FROM {$goods_table}
		WHERE state>=20
		AND dateline BETWEEN {$start_time} AND {$end_time}
		GROUP BY source HAVING COUNT(*)>0
		) g
		LEFT JOIN {$dbprefix}{$this::$table_name} AS s ON (g.source=s.id)";
		
		$data = $this->db->query($query)->row_array();
		
		return $data['count'];
	}
	
}