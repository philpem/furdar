

CREATE TABLE group_in_legacy_location (
	group_id INTEGER NOT NULL,
	legacy_location_id INTEGER NOT NULL,
	added_by_user_account_id INTEGER NULL,
	added_at timestamp without time zone NOT NULL,
	removed_by_user_account_id INTEGER NULL,
	removed_at timestamp without time zone NULL,	
	added_by_event_id INTEGER NULL,
	PRIMARY KEY(group_id,legacy_location_id,added_at)
);

ALTER TABLE group_in_legacy_location  ADD CONSTRAINT group_in_legacy_location_group_id FOREIGN KEY (group_id) REFERENCES group_information(id);
ALTER TABLE group_in_legacy_location  ADD CONSTRAINT group_in_legacy_location_legacy_location_id FOREIGN KEY (legacy_location_id) REFERENCES legacy_location_information(id);
ALTER TABLE group_in_legacy_location  ADD CONSTRAINT group_in_legacy_location_event_id FOREIGN KEY (added_by_event_id) REFERENCES event_information(id);

ALTER TABLE group_in_legacy_location  ADD CONSTRAINT group_in_legacy_location_added_by_user_account_id FOREIGN KEY (added_by_user_account_id) REFERENCES user_account_information(id);
ALTER TABLE group_in_legacy_location  ADD CONSTRAINT group_in_legacy_location_removed_by_user_account_id FOREIGN KEY (removed_by_user_account_id) REFERENCES user_account_information(id);


