-- =====================================================
-- ICT ASSET MANAGEMENT - AUDIT TRAIL SYSTEM
-- ENTERPRISE LEVEL SCRIPT
-- =====================================================

USE ict_asset_management;

-- =====================================================
-- 1. ASSET STATUS HISTORY TABLE
-- =====================================================

CREATE TABLE IF NOT EXISTS asset_status_history (
                                                    history_id INT AUTO_INCREMENT PRIMARY KEY,
                                                    asset_id INT NOT NULL,
                                                    old_status_id INT,
                                                    new_status_id INT,
                                                    changed_by INT,
                                                    change_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                                                    remarks TEXT,

                                                    FOREIGN KEY (asset_id) REFERENCES assets(asset_id) ON DELETE CASCADE,
                                                    FOREIGN KEY (changed_by) REFERENCES users(user_id) ON DELETE SET NULL
) ENGINE=InnoDB;

-- =====================================================
-- 2. ASSET ASSIGNMENT HISTORY TABLE
-- =====================================================

CREATE TABLE IF NOT EXISTS asset_assignment_history (
                                                        history_id INT AUTO_INCREMENT PRIMARY KEY,
                                                        asset_id INT NOT NULL,
                                                        user_id INT,
                                                        action ENUM('ASSIGNED','RETURNED'),
                                                        action_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                                                        remarks TEXT,

                                                        FOREIGN KEY (asset_id) REFERENCES assets(asset_id) ON DELETE CASCADE,
                                                        FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE SET NULL
) ENGINE=InnoDB;

-- =====================================================
-- 3. GENERAL ASSET AUDIT LOG TABLE
-- =====================================================

CREATE TABLE IF NOT EXISTS asset_audit_logs (
                                                audit_id INT AUTO_INCREMENT PRIMARY KEY,
                                                asset_id INT NOT NULL,
                                                field_name VARCHAR(100),
                                                old_value TEXT,
                                                new_value TEXT,
                                                changed_by INT,
                                                change_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

                                                FOREIGN KEY (asset_id) REFERENCES assets(asset_id) ON DELETE CASCADE,
                                                FOREIGN KEY (changed_by) REFERENCES users(user_id) ON DELETE SET NULL
) ENGINE=InnoDB;

-- =====================================================
-- 4. PERFORMANCE INDEXING FOR AUDIT TABLES
-- =====================================================

CREATE INDEX idx_status_history_asset ON asset_status_history(asset_id);
CREATE INDEX idx_assign_history_asset ON asset_assignment_history(asset_id);
CREATE INDEX idx_audit_asset ON asset_audit_logs(asset_id);

-- =====================================================
-- 5. TRIGGERS FOR AUTOMATIC AUDIT TRACKING
-- =====================================================

DELIMITER $$

-- -----------------------------------------------------
-- STATUS CHANGE TRACKING TRIGGER
-- -----------------------------------------------------
CREATE TRIGGER trg_status_change
    BEFORE UPDATE ON assets
    FOR EACH ROW
BEGIN
    IF OLD.status_id <> NEW.status_id THEN
        INSERT INTO asset_status_history
        (asset_id, old_status_id, new_status_id, changed_by)
        VALUES (OLD.asset_id, OLD.status_id, NEW.status_id, 1);
    END IF;
END$$

-- -----------------------------------------------------
-- ASSET ASSIGNMENT TRACKING TRIGGER
-- -----------------------------------------------------
CREATE TRIGGER trg_asset_assignment
    AFTER INSERT ON asset_assignments
    FOR EACH ROW
BEGIN
    INSERT INTO asset_assignment_history
    (asset_id, user_id, action)
    VALUES (NEW.asset_id, NEW.user_id, 'ASSIGNED');
END$$

-- -----------------------------------------------------
-- ASSET RETURN TRACKING TRIGGER
-- -----------------------------------------------------
CREATE TRIGGER trg_asset_return
    AFTER UPDATE ON asset_assignments
    FOR EACH ROW
BEGIN
    IF OLD.returned_date IS NULL AND NEW.returned_date IS NOT NULL THEN
        INSERT INTO asset_assignment_history
        (asset_id, user_id, action)
        VALUES (NEW.asset_id, NEW.user_id, 'RETURNED');
    END IF;
END$$

-- -----------------------------------------------------
-- GENERAL FIELD CHANGE TRACKING TRIGGER
-- -----------------------------------------------------
CREATE TRIGGER trg_asset_general_update
    AFTER UPDATE ON assets
    FOR EACH ROW
BEGIN
    IF OLD.location_id <> NEW.location_id THEN
        INSERT INTO asset_audit_logs
        (asset_id, field_name, old_value, new_value, changed_by)
        VALUES (OLD.asset_id, 'location_id', OLD.location_id, NEW.location_id, 1);
    END IF;

    IF OLD.vendor_id <> NEW.vendor_id THEN
        INSERT INTO asset_audit_logs
        (asset_id, field_name, old_value, new_value, changed_by)
        VALUES (OLD.asset_id, 'vendor_id', OLD.vendor_id, NEW.vendor_id, 1);
    END IF;

    IF OLD.cost <> NEW.cost THEN
        INSERT INTO asset_audit_logs
        (asset_id, field_name, old_value, new_value, changed_by)
        VALUES (OLD.asset_id, 'cost', OLD.cost, NEW.cost, 1);
    END IF;
END$$

DELIMITER ;

-- =====================================================
-- END OF AUDIT TRAIL SCRIPT
-- =====================================================