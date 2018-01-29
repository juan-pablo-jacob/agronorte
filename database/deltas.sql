ALTER TABLE `cotizacion`
ADD COLUMN `precio_lista_producto_iva`  double NULL DEFAULT NULL AFTER `precio_toma_iva`;


--Actualización de los precio de venta
update cotizacion
set precio_venta = (100 - descuento) * precio_lista_producto / 100
where is_toma <> 1;

--actualización de precio venta IVA y precio lista producto IVA
update cotizacion
set precio_venta_iva = precio_venta * (100 + 10.5) / 100,
precio_lista_producto_iva = precio_lista_producto * (100 + 10.5) / 100
where is_toma <> 1;