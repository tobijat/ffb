-- Hash API keys at rest (SHA-256 hex).
-- Safe to re-run: only transforms legacy non-64-hex values.
UPDATE `ffb_apikey`
SET `apikey_key` = SHA2(`apikey_key`, 256)
WHERE `apikey_key` NOT REGEXP '^[a-fA-F0-9]{64}$';
