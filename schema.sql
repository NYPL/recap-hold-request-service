CREATE SEQUENCE recap_hold_request_id_seq;

CREATE TABLE recap_hold_request (
  id INTEGER PRIMARY KEY NOT NULL DEFAULT nextval('recap_hold_request_id_seq'::regclass),
  tracking_id TEXT,
  patron_barcode TEXT,
  item_barcode TEXT,
  created_date TEXT,
  update_date TEXT,
  owning_institution_id TEXT, 
  description TEXT
);

CREATE INDEX recap_hold_request_ids_idx ON recap_hold_request (id, tracking_id);
