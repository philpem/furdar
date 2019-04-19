ALTER TABLE legacy_region_information ADD area_id INTEGER NULL;
ALTER TABLE legacy_region_information ADD CONSTRAINT legacy_region_information_area_id FOREIGN KEY (area_id) REFERENCES area_information(id);


ALTER TABLE legacy_location_information ADD area_id INTEGER NULL;
ALTER TABLE legacy_location_information ADD CONSTRAINT legacy_location_information_area_id FOREIGN KEY (area_id) REFERENCES area_information(id);
