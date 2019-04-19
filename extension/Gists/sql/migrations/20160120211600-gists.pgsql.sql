
CREATE TABLE gist_information (
	id SERIAL NOT NULL,
	site_id INTEGER NOT NULL,
	slug VARCHAR(255) NOT NULL,
	title VARCHAR(255),
	is_deleted boolean default '0' NOT NULL,
	created_at timestamp without time zone NOT NULL,
	PRIMARY KEY(id)
);
ALTER TABLE gist_information ADD CONSTRAINT gist_information_site_id FOREIGN KEY (site_id) REFERENCES site_information(id);
CREATE UNIQUE INDEX gist_information_slug ON gist_information(site_id, slug);


CREATE TABLE gist_content_information (
	id SERIAL NOT NULL,
	gist_id INTEGER NOT NULL,
  sort  SMALLINT NOT NULL DEFAULT 0,
	event_id INTEGER NULL,
	group_id INTEGER NULL,
	area_id INTEGER NULL,
	venue_id INTEGER NULL,
	content_title VARCHAR(255) NULL,
  content_text TEXT NULL,
	created_at timestamp without time zone NOT NULL,
	PRIMARY KEY(id)
);
ALTER TABLE gist_content_information ADD CONSTRAINT gist_content_information_gist_id FOREIGN KEY (gist_id) REFERENCES gist_information(id);
ALTER TABLE gist_content_information ADD CONSTRAINT gist_content_information_group_id FOREIGN KEY (group_id) REFERENCES group_information(id);
ALTER TABLE gist_content_information ADD CONSTRAINT gist_content_information_area_id FOREIGN KEY (area_id) REFERENCES area_information(id);
ALTER TABLE gist_content_information ADD CONSTRAINT gist_content_information_event_id FOREIGN KEY (event_id) REFERENCES event_information(id);
ALTER TABLE gist_content_information ADD CONSTRAINT gist_content_information_venue_id FOREIGN KEY (venue_id) REFERENCES venue_information(id);

CREATE TABLE user_in_gist_information (
	user_account_id INTEGER NOT NULL,
	gist_id INTEGER NOT NULL,
	is_owner BOOLEAN DEFAULT '0' NOT NULL,
	is_editor BOOLEAN DEFAULT '0' NOT NULL,
	created_at timestamp without time zone NOT NULL,
	PRIMARY KEY(user_account_id,gist_id)
);
ALTER TABLE user_in_gist_information ADD CONSTRAINT user_in_gist_information_user_account_id FOREIGN KEY (user_account_id) REFERENCES user_account_information(id);
ALTER TABLE user_in_gist_information ADD CONSTRAINT user_in_gist_information_gist_id FOREIGN KEY (gist_id) REFERENCES gist_information(id);
