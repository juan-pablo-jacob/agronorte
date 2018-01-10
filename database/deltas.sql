ALTER TABLE `cotizacion`
ADD COLUMN `precio_lista_producto_iva`  double NULL DEFAULT NULL AFTER `precio_toma_iva`;