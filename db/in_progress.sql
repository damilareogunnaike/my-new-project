SELECT
a.subject_id, GROUP_CONCAT(a.total_score),
SUM(a.total_score) AS total_score,
GROUP_CONCAT(a.term_id) FROM subject_result_overview a,
(SELECT (SUM(total_score)) FROM subject_result_overview b WHERE b.session_id = 4 AND b.class_id = 39) AS class_max_score,
(SELECT (SUM(total_score)) FROM subject_result_overview b WHERE b.session_id = 4 AND b.class_id = 39) AS class_min_score,
(SELECT (SUM(total_score)) FROM subject_result_overview b WHERE b.session_id = 4 AND b.class_id = 39) AS class_avg_score
WHERE a.session_id = 4 AND a.class_id = 39
GROUP BY a.subject_id