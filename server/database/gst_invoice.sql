-- PostgreSQL Dump for GST Invoice
-- Converted from MySQL/MariaDB

BEGIN;

CREATE TYPE invoice_status AS ENUM ('draft', 'saved', 'cancelled');

CREATE TABLE IF NOT EXISTS companies (
    id               SERIAL PRIMARY KEY,
    company_name     VARCHAR(255)  NOT NULL,
    company_address  TEXT          NOT NULL DEFAULT '',
    gst_number       VARCHAR(20)   NOT NULL DEFAULT '',
    phone            VARCHAR(30)   NOT NULL DEFAULT '',
    email            VARCHAR(150)  NOT NULL DEFAULT '',
    logo_path        VARCHAR(500)  NOT NULL DEFAULT '',
    bank_account     VARCHAR(30)   NOT NULL DEFAULT '',
    bank_ifsc        VARCHAR(20)   NOT NULL DEFAULT '',
    bank_branch      VARCHAR(150)  NOT NULL DEFAULT '',
    proprietor       VARCHAR(150)  NOT NULL DEFAULT 'Proprietor',
    is_active        BOOLEAN       NOT NULL DEFAULT TRUE,
    created_at       TIMESTAMP     NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at       TIMESTAMP     NOT NULL DEFAULT CURRENT_TIMESTAMP
);

INSERT INTO companies (
    id, company_name, company_address, gst_number, phone, email,
    logo_path, bank_account, bank_ifsc, bank_branch, proprietor,
    is_active, created_at, updated_at
) VALUES (
    1,
    'Your Company Name',
    '123, Main Street, Chennai, Tamil Nadu - 600001',
    '33AAAAA0000A1Z5',
    '+91 98765 43210',
    'info@yourcompany.com',
    '',
    '000000000000',
    'SBIN0001234',
    'State Bank of India, Anna Nagar',
    'Proprietor',
    TRUE,
    '2026-03-28 10:49:33',
    '2026-03-28 10:49:33'
) ON CONFLICT (id) DO NOTHING;

SELECT setval('companies_id_seq', (SELECT MAX(id) FROM companies));

CREATE TABLE IF NOT EXISTS invoice_counters (
    id          SERIAL PRIMARY KEY,
    company_id  INTEGER       NOT NULL DEFAULT 1,
    year        SMALLINT      NOT NULL,
    last_number INTEGER       NOT NULL DEFAULT 0,
    CONSTRAINT uq_company_year UNIQUE (company_id, year)
);

INSERT INTO invoice_counters (id, company_id, year, last_number)
VALUES (1, 1, 2026, 4) ON CONFLICT DO NOTHING;

SELECT setval('invoice_counters_id_seq', (SELECT MAX(id) FROM invoice_counters));

CREATE TABLE IF NOT EXISTS invoices (
    id              SERIAL PRIMARY KEY,
    company_id      INTEGER          NOT NULL DEFAULT 1,
    invoice_number  VARCHAR(50)      NOT NULL,
    invoice_title   VARCHAR(100)     NOT NULL DEFAULT 'Tax Invoice',
    template        VARCHAR(10)      NOT NULL DEFAULT 't01',
    invoice_date    DATE             NOT NULL,
    created_at      TIMESTAMP        NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at      TIMESTAMP        NOT NULL DEFAULT CURRENT_TIMESTAMP,
    bill_to         TEXT             NOT NULL DEFAULT '',
    vehicle_number  VARCHAR(30)      NOT NULL DEFAULT '',
    subtotal        NUMERIC(14,2)    NOT NULL DEFAULT 0.00,
    cgst_total      NUMERIC(14,2)    NOT NULL DEFAULT 0.00,
    sgst_total      NUMERIC(14,2)    NOT NULL DEFAULT 0.00,
    total           NUMERIC(14,2)    NOT NULL DEFAULT 0.00,
    payment_made    NUMERIC(14,2)    NOT NULL DEFAULT 0.00,
    balance_due     NUMERIC(14,2)    NOT NULL DEFAULT 0.00,
    amount_words    TEXT             NOT NULL DEFAULT '',
    notes           TEXT             NOT NULL DEFAULT '',
    status          invoice_status   NOT NULL DEFAULT 'saved',
    CONSTRAINT uq_invoice_number UNIQUE (company_id, invoice_number)
);

CREATE TABLE IF NOT EXISTS invoice_items (
    id           SERIAL PRIMARY KEY,
    invoice_id   INTEGER         NOT NULL,
    serial       SMALLINT        NOT NULL DEFAULT 1,
    description  VARCHAR(500)    NOT NULL,
    hsn          VARCHAR(20)     NOT NULL DEFAULT '',
    quantity     NUMERIC(12,3)   NOT NULL DEFAULT 1.000,
    per_unit     VARCHAR(30)     NOT NULL DEFAULT '',
    rate         NUMERIC(14,2)   NOT NULL DEFAULT 0.00,
    base_amount  NUMERIC(14,2)   NOT NULL DEFAULT 0.00,
    cgst_rate    NUMERIC(5,2)    NOT NULL DEFAULT 3.00,
    sgst_rate    NUMERIC(5,2)    NOT NULL DEFAULT 3.00,
    cgst_amount  NUMERIC(14,2)   NOT NULL DEFAULT 0.00,
    sgst_amount  NUMERIC(14,2)   NOT NULL DEFAULT 0.00,
    amount       NUMERIC(14,2)   NOT NULL DEFAULT 0.00
);

ALTER TABLE invoices DROP CONSTRAINT IF EXISTS fk_invoice_company;
ALTER TABLE invoices ADD CONSTRAINT fk_invoice_company FOREIGN KEY (company_id) REFERENCES companies (id) ON UPDATE CASCADE;

ALTER TABLE invoice_counters DROP CONSTRAINT IF EXISTS fk_counter_company;
ALTER TABLE invoice_counters ADD CONSTRAINT fk_counter_company FOREIGN KEY (company_id) REFERENCES companies (id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE invoice_items DROP CONSTRAINT IF EXISTS fk_item_invoice;
ALTER TABLE invoice_items ADD CONSTRAINT fk_item_invoice FOREIGN KEY (invoice_id) REFERENCES invoices (id) ON DELETE CASCADE ON UPDATE CASCADE;

CREATE INDEX IF NOT EXISTS idx_item_invoice ON invoice_items (invoice_id);

CREATE OR REPLACE FUNCTION sp_next_invoice_number(
    p_company_id  INTEGER,
    p_year        SMALLINT
)
RETURNS VARCHAR(50) AS $$
DECLARE
    v_next INTEGER;
BEGIN
    INSERT INTO invoice_counters (company_id, year, last_number)
    VALUES (p_company_id, p_year, 1)
    ON CONFLICT (company_id, year)
    DO UPDATE SET last_number = invoice_counters.last_number + 1
    RETURNING last_number INTO v_next;

    RETURN 'INV-' || LPAD(v_next::TEXT, 4, '0');
END;
$$ LANGUAGE plpgsql;

COMMIT;
