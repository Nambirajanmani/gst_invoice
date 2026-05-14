-- PostgreSQL Dump
-- Converted from MySQL/MariaDB
-- Database: gst_invoice

BEGIN;

-- --------------------------------------------------------
-- ENUM TYPE
-- --------------------------------------------------------

CREATE TYPE invoice_status AS ENUM ('draft', 'saved', 'cancelled');

-- --------------------------------------------------------
-- TABLE: companies
-- --------------------------------------------------------

CREATE TABLE companies (
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
);

-- Keep SERIAL sequence in sync after manual insert
SELECT setval('companies_id_seq', (SELECT MAX(id) FROM companies));

-- --------------------------------------------------------
-- TABLE: invoice_counters
-- --------------------------------------------------------

CREATE TABLE invoice_counters (
    id          SERIAL PRIMARY KEY,
    company_id  INTEGER       NOT NULL DEFAULT 1,
    year        SMALLINT      NOT NULL,
    last_number INTEGER       NOT NULL DEFAULT 0,
    CONSTRAINT uq_company_year UNIQUE (company_id, year)
);

INSERT INTO invoice_counters (id, company_id, year, last_number)
VALUES (1, 1, 2026, 4);

SELECT setval('invoice_counters_id_seq', (SELECT MAX(id) FROM invoice_counters));

-- --------------------------------------------------------
-- TABLE: invoices
-- --------------------------------------------------------

CREATE TABLE invoices (
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

-- --------------------------------------------------------
-- TABLE: invoice_items
-- --------------------------------------------------------

CREATE TABLE invoice_items (
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

-- --------------------------------------------------------
-- FOREIGN KEY CONSTRAINTS
-- --------------------------------------------------------

ALTER TABLE invoices
    ADD CONSTRAINT fk_invoice_company
    FOREIGN KEY (company_id) REFERENCES companies (id) ON UPDATE CASCADE;

ALTER TABLE invoice_counters
    ADD CONSTRAINT fk_counter_company
    FOREIGN KEY (company_id) REFERENCES companies (id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE invoice_items
    ADD CONSTRAINT fk_item_invoice
    FOREIGN KEY (invoice_id) REFERENCES invoices (id) ON DELETE CASCADE ON UPDATE CASCADE;

-- --------------------------------------------------------
-- INDEXES
-- --------------------------------------------------------

CREATE INDEX idx_item_invoice ON invoice_items (invoice_id);

-- --------------------------------------------------------
-- AUTO-UPDATE updated_at TRIGGER (replaces ON UPDATE current_timestamp)
-- --------------------------------------------------------

CREATE OR REPLACE FUNCTION fn_set_updated_at()
RETURNS TRIGGER AS $$
BEGIN
    NEW.updated_at = CURRENT_TIMESTAMP;
    RETURN NEW;
END;
$$ LANGUAGE plpgsql;

CREATE TRIGGER trg_companies_updated_at
    BEFORE UPDATE ON companies
    FOR EACH ROW EXECUTE FUNCTION fn_set_updated_at();

CREATE TRIGGER trg_invoices_updated_at
    BEFORE UPDATE ON invoices
    FOR EACH ROW EXECUTE FUNCTION fn_set_updated_at();

-- --------------------------------------------------------
-- FUNCTION: sp_next_invoice_number
-- --------------------------------------------------------

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

-- Usage: SELECT sp_next_invoice_number(1, 2026);

-- --------------------------------------------------------
-- VIEW: v_invoice_list
-- --------------------------------------------------------

CREATE OR REPLACE VIEW v_invoice_list AS
SELECT
    i.id,
    i.invoice_number,
    i.invoice_title,
    i.invoice_date,
    i.bill_to,
    i.total,
    i.payment_made,
    i.balance_due,
    i.status,
    i.template,
    i.created_at,
    COUNT(ii.id) AS item_count
FROM invoices i
LEFT JOIN invoice_items ii ON ii.invoice_id = i.id
GROUP BY i.id;

-- --------------------------------------------------------
-- VIEW: v_monthly_revenue
-- --------------------------------------------------------

CREATE OR REPLACE VIEW v_monthly_revenue AS
SELECT
    EXTRACT(YEAR  FROM invoice_date)::INTEGER  AS yr,
    EXTRACT(MONTH FROM invoice_date)::INTEGER  AS mo,
    COUNT(*)                                   AS invoice_count,
    SUM(subtotal)                              AS subtotal,
    SUM(cgst_total)                            AS cgst,
    SUM(sgst_total)                            AS sgst,
    SUM(total)                                 AS gross_total,
    SUM(payment_made)                          AS collected,
    SUM(balance_due)                           AS outstanding
FROM invoices
WHERE status <> 'cancelled'
GROUP BY yr, mo
ORDER BY yr DESC, mo DESC;

COMMIT;
