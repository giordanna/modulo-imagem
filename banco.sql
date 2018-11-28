-- -----------------------------------------------------
-- Table `mydb`.`admins`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `admins` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `email` VARCHAR(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `senha` VARCHAR(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
                 )
ENGINE = InnoDB;

-- -----------------------------------------------------
-- Table `mydb`.`imagens`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `imagens` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `admins_id` INT NOT NULL,
  `nome` VARCHAR(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `autor` VARCHAR(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL, 
  `data_obra` DATE NULL,
  `descricao` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `data_criada` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `largura` INT NOT NULL,
  `altura` INT NOT NULL,
  `id_string` VARCHAR(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  PRIMARY KEY (`id`, `admins_id`),
  CONSTRAINT `fk_imagens_admins`
    FOREIGN KEY (`admins_id`)
    REFERENCES `admins` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `mydb`.`fragmentos`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `fragmentos` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `imagens_id` INT NOT NULL,
  `numero` INT NOT NULL,
  `x` INT NOT NULL,
  `y` INT NOT NULL,
  `largura` INT NOT NULL,
  `altura` INT NOT NULL,
  `html` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  PRIMARY KEY (`id`, `imagens_id`),
  CONSTRAINT `fk_fragmentos_imagens`
    FOREIGN KEY (`imagens_id`)
    REFERENCES `imagens` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;