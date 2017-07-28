<div id="right_column">
    <div id="archive" class="section desktop">
        <h3 class="section_title"><?php echo $lng['blog_archive']; ?></h3>
        <div class='entry'>
            <?php
                $q_year = mysqli_query($con, "SELECT year(dtime) AS year FROM post WHERE visible = 1 GROUP BY year(dtime) ORDER BY year DESC;");
                while($r_year = mysqli_fetch_array($q_year)){
                    echo "<div class='year pointer' onClick=\"toggleElement('year_$r_year[year]');\">";
                    echo ("<img class='slid' id='slid_year_$r_year[year]' src='$proto$http_host/img/misc/slid-right.png' alt=' '/><span class='fake_a'>$r_year[year]</span>");
                    echo "</div>\n";
                    echo "<div class='list_year pointer' id='list_year_$r_year[year]'>\n";
                    $q_month = mysqli_query($con, "SELECT month(dtime) AS month FROM post WHERE visible = 1 AND year(dtime) = $r_year[year] GROUP BY month(dtime) ORDER BY month DESC;");
                    while($r_month = mysqli_fetch_array($q_month)){
                        echo "<div class='month pointer' onClick=\"toggleElement('month_$r_year[year]_$r_month[month]');\">";
                        echo ("<img class='slid' id='slid_month_$r_year[year]_$r_month[month]' src='$proto$http_host/img/misc/slid-right.png' alt=' '/><span class='fake_a'>" . $lng['months'][$r_month['month'] - 1] . "</span>");
                        echo "</div>\n";
                        echo "<ul id='list_month_$r_year[year]_$r_month[month]' class='post_list'>\n";
                        $q_title = mysqli_query($con, "SELECT id, permalink, title_$lang AS title FROM post WHERE visible = 1 AND year(dtime) = $r_year[year] AND month(dtime) = '$r_month[month]' ORDER BY dtime DESC;");
                        while($r_title = mysqli_fetch_array($q_title))
                            echo "<li><a href='$proto$http_host/blog/" . $r_title['permalink']  . "'>$r_title[title]</a></li>\n";
                        echo "</ul>\n";
                    }
                    echo "</div>\n";
                }
            ?>
        </div>
    </div>
</div>
