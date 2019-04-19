CREATE TABLE legacy_region_information (
	id SERIAL,
	title VARCHAR(255) NULL,
	country_id INTEGER,
	PRIMARY KEY(id)
);
ALTER TABLE legacy_region_information ADD CONSTRAINT legacy_region_information_country_id FOREIGN KEY (country_id) REFERENCES country(id);


CREATE TABLE legacy_location_information (
	id SERIAL,
	title VARCHAR(255) NULL,
	country_id INTEGER,
	legacy_region_id INTEGER,
	post_code_filter VARCHAR(255) NULL,
	lat REAL,
	lng REAL,
	cached_future_events INTEGER,
	PRIMARY KEY(id)
);
ALTER TABLE legacy_location_information ADD CONSTRAINT legacy_location_information_country_id FOREIGN KEY (country_id) REFERENCES country(id);
ALTER TABLE legacy_location_information ADD CONSTRAINT legacy_location_information_legacy_region_id FOREIGN KEY (legacy_region_id) REFERENCES legacy_region_information(id);


ALTER TABLE event_information ADD legacy_location_id INT NULL;
ALTER TABLE event_history ADD legacy_location_id INT NULL;

ALTER TABLE event_information ADD CONSTRAINT event_information_legacy_location_id FOREIGN KEY (legacy_location_id) REFERENCES legacy_location_information(id);
ALTER TABLE event_history ADD CONSTRAINT event_history_legacy_location_id FOREIGN KEY (legacy_location_id) REFERENCES legacy_location_information(id);


ALTER TABLE import_url_information ADD legacy_location_id INT NULL;
ALTER TABLE import_url_history ADD legacy_location_id INT NULL;

ALTER TABLE import_url_information ADD CONSTRAINT import_url_information_legacy_location_id FOREIGN KEY (legacy_location_id) REFERENCES legacy_location_information(id);
ALTER TABLE import_url_history ADD CONSTRAINT import_url_history_legacy_location_id FOREIGN KEY (legacy_location_id) REFERENCES legacy_location_information(id);
