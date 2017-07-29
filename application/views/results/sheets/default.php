<style>

    @media print and(color) {
        * {
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
            font-weight: 600;
        }

        .text-danger {
            color: red;
        }
    }

    .result {
        margin-top: 30px;
    }

</style>

<div class="container result">
    <div class="report">

        <div class="row " style="margin:0">
            <div class="col-md-12 text-center">
                <?= img(array("src" => $school_info['school_logo'], "class" => "img")); ?>
                <h2><?= get_arr_value($school_info, "school_name"); ?></h2>
                <p>
                    <span class="text-italics"><i><?= get_arr_value($school_info, "school_motto") ?></i></span><br>
                    <span class="text-italics"><i><?= get_arr_value($school_info, "school_address"); ?></i></span>
                </p>
                <!--
                    <p>
                        <?= isset($school_email) ? $school_email : ''; ?> |
                        <?= isset($school_phone_no) ? $school_phone_no : ''; ?><br>
                        <?= isset($school_website) ? $school_website : ''; ?> | <?= isset($school_address) ? $school_address : ''; ?>
                    </p> -->
            </div>
        </div>
        <div class="row">
            <div class="col-md-6 text-left">
                <?php if ($result_display['picture'] == 1) { ?>
                    <?= img(array('src' => $biodata['image'], 'class' => 'img-passport pull-left img-thumbnail', 'style' => 'margin-right:20px;')); ?>
                <?php } ?>
                <h4 style="font-weight:bold;">
                    <?php if ($result_display['full_name'] == 1) { ?>
                        <?= $student['student_name']; ?>
                    <?php } ?>
                </h4>
                <?php if ($result_display['class'] == 1) { ?>
                    <h4><?= $class['class_name']; ?></h4>
                <?php } ?>
                <?php if ($result_display['date_of_birth'] == 1) { ?>
                    <h4><?= $student['date_of_birth']; ?></h4>
                <?php } ?>
                <?php if ($result_display['gender'] == 1) { ?>
                    <h4><?= $student['gender']; ?></h4>
                <?php } ?>
            </div>
            <div class="col-md-6 text-right">

            </div>
        </div>
        <div class="row">
            <div class="col-md-12">

                <?php if (!isset($result_table) || (isset($result_table) && $result_table != "all_terms")) {
                    ?>
                    <table class="table table-bordered">
                    <tr>
                        <th colspan="100%" class="text-center">
                            <?= isset($result_info) ? $result_info['session'] : ''; ?>
                            | <?= isset($result_info) ? $result_info['term'] : ''; ?>
                        </th>
                    </tr>
                    <tr>
                        <th>#</th>
                        <th>Subject</th>
                        <?php if (!$is_cumulative) {
                            ; ?>
                            <th>CA-1</th>
                            <th>CA-2</th>
                            <th>Exam</th>
                            <th>Class Max</th>
                            <th>Class Avg.</th>
                            <th>Class Min.</th>
                        <?php } else {
                            foreach ($term_keys as $term) {
                                ?>
                                <th><?= $term['school_term_name']; ?></th>
                                <?php
                            }
                        } ?>
                        <th class="lg-padding">Total</th>
                        <th>Position</th>
                        <th>Grade</th>
                        <th>Comment</th>
                        <?php if ($result_display['teacher_name'] == 1) { ?>
                            <th>Subject Teacher</th>
                        <?php } ?>
                    </tr>
                    <tbody>
                    <?php
                    $count = 0;
                    if (isset($subject_scores) && sizeof($subject_scores) > 0) {
                        foreach ($subject_scores as $score) {
                            ?>
                            <tr>
                                <td><?= ++$count; ?></td>
                                <td><?= $score['subject_name']; ?></td>

                                <?php
                                if ($is_cumulative) {
                                    $scores = $score['scores'];
                                    foreach ($term_keys as $term) {
                                        $key = $term['school_term_id'];
                                        $term_score = isset($scores[$key]) ? $scores[$key] : 0;
                                        ?>
                                        <td><?= $term_score; ?></td>
                                        <?php
                                    }
                                }
                                else {
                                    ?>
                                    <td><?= $score['ca1']; ?></td>
                                    <td><?= $score['ca2']; ?></td>
                                    <td><?= $score['exam']; ?></td>
                                    <td><?= number_format($score['class_max_score'], 2); ?></td>
                                    <td><?= number_format($score['class_avg_score'], 2); ?></td>
                                    <td><?= number_format($score['class_min_score'], 2); ?></td>
                                    <?php
                                } ?>
                                <td><?= number_format($score['total_score'], 2); ?></td>

                                <td><?= print_position($score['position']); ?></td>
                                <td><?= get_grade($is_cumulative ? $score['avg_score'] : $score['total_score']); ?></td>
                                <td><?= get_comment($is_cumulative ? $score['avg_score'] : $score['total_score']); ?></td>
                                <?php if ($result_display['teacher_name'] == 1) { ?>
                                    <td><?= $score['staff_name']; ?></td>
                                <?php } ?>
                            </tr>

                            <?php
                        }
                        ?>
                        </tbody>
                        </table>
                    <?php }
                } else if (isset($result_table) && $result_table == "all_terms") {
                    echo $this->load->view('results/all_terms', NULL, true);
                } ?>
                <hr/>
                <div class="row">
                    <div class="col-md-8" style="width:70%">
                        <table class="table table-bordered table-responsive table-condensed pull-left">
                            <tr>
                                <th colspan="100%" class="text-center">Result Summary</th>
                            </tr>
                            <tr>
                                <td>Max.
                                    Obtainable: <?= number_format($student_report_overview['max_obtainable'], 2); ?></td>
                                <td>Total Score: <?= number_format($student_report_overview['total_score'], 2); ?></td>
                                <td>Max Score: <?= $student_report_overview['max_score']; ?></td>
                                <td>Min Score: <?= $student_report_overview['min_score']; ?></td>
                                <td>Position in Class: <?= print_position($student_report_overview['position']); ?></td>
                            </tr>
                            <tr>

                                <td>Avg Score: <?= number_format($student_report_overview['avg_score'], 2); ?></td>
                                <td>Students in Class: <?= $class_report_overview['class_size']; ?></td>
                                <td>Class Max Score: <?= number_format($class_report_overview['max_score'], 2); ?></td>
                                <td>Class Min Score: <?= number_format($class_report_overview['min_score'], 2); ?></td>
                                <td>Class Avg Score: <?= number_format($class_report_overview['avg_score'], 2); ?></td>
                            </tr>
                            <tr>
                                <td>Principal's Comment:</td>
                                <td colspan="100%"><?= $student_report_overview['comment']; ?></td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-4" style="width:30%; float:right">
                        <?php if (isset($result_display['cognitive_skills']) && $result_display['cognitive_skills'] == 1) { ?>
                            <table class="table table-bordered pull-right">
                                <tr>
                                    <th>#</th>
                                    <th>Skill</th>
                                    <th>Rating</th>
                                </tr>
                                <tbody>
                                <?php
                                if (isset($cog_skills_report) && sizeof($cog_skills_report)) {
                                    $count = 0;
                                    foreach ($cog_skills_report as $record) {
                                        ?>
                                        <tr>
                                            <td><?= ++$count; ?></td>
                                            <td>
                                                <?= $record['skill']; ?>
                                            </td>
                                            <td>
                                                <?= ($record['rating'] != NULL) ? floor($record['rating']) : 0; ?>
                                            </td>
                                        </tr>
                                        <?php
                                    }
                                }
                                ?>
                                </tbody>
                            </table>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>