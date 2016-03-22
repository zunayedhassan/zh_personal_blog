USE minefield_db;

CREATE TABLE blog
(
	blog_id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
	title varchar(255) NOT NULL,
	published DATETIME NOT NULL,
	content MEDIUMTEXT NOT NULL,
	tag TEXT,
	UNIQUE (blog_id)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

INSERT INTO Blog(title, published, content, tag) VALUES('sample blog title', CURRENT_TIMESTAMP, 'sample blog content', 'test1, test2, test3');

SELECT title, published, tag FROM blog;

DELETE FROM Blog WHERE blog_id = 1;

SELECT * FROM Blog ORDER BY published DESC;

-- ----------------------------------------------------

SELECT * FROM blog WHERE title LIKE '%hello%' OR content LIKE '%hello%' OR tag LIKE '%hello%' ORDER BY published DESC;

-- ----------------------------------------------------

CREATE TABLE about_me
(
	title varchar(255),
	content MEDIUMTEXT
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

INSERT INTO about_me(title, content) VALUES('Who is this guy?', '<div class="content">
                <div id="authorPicture"><img src="images/users_images/zunayed_hassan_goldcoast_in_au.png" alt="Mohammod Zunayed Hassan" width="128px" /></div>

                <table id="aboutMeTable" class="aboutMyself">
                    <tr>
                        <th>WHO //</th>
                        <td>Mohammod Zunayed Hassan</td>
                    </tr>

                    <tr>
                        <th>WHAT //</th>
                        <td>Web Designer & Front End Developer</td>
                    </tr>

                    <tr>
                        <th>WHERE //</th>
                        <td><address>Dhaka, Bangladesh</address></td>
                    </tr>

                    <tr>
                        <th>WHEN //</th>
                        <td><time datetime="2013">2013</time> - Present</td>
                    </tr>

                    <tr>
                        <th>WHY //</th>
                        <td>Passion</td>
                    </tr>
                </table>

                <h3>Contact Me</h3>
                <p>
                    <span class="title">Email:</span> <a href="mailto:zunayed-hassan@live.com?subject=Feedback">zunayed-hassan@live.com</a><br/>
                    <span class="title">Phone:</span> +880 1741284439
                </p>
            </div>');