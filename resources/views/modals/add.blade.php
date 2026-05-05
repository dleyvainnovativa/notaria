<div class="modal fade" id="modalAgregar" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-fullscreen-sm-down">
        <div class="modal-content">
            <div class="modal-header text-bg-primary">
                <h5 class="modal-title fw-bold">Agregar gasto</h5>
                <button class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <label class="form-label fw-semibold">Tipo de gasto</label>
                <select id="addTipo" class="form-select mb-3">
                    <option value="" disabled selected>Seleccionar</option>
                    <option value="factura">Gastos con factura</option>
                    <option value="no_factura">Gastos sin factura</option>
                </select>
                <div id="addFactura" class="d-none">
                    <label class="form-label">Categoría</label>
                    <select id="addFacturaTipo" class="form-select mb-3">
                        <option value="HOTEL">HOTEL</option>
                        <option value="TRANSPORTACION_TERRESTRE_GENERAL">
                            TRANSPORTACION TERRESTRE (AUTOBUS, TAXI, ESTACIONAMIENTO)
                        </option>
                        <option value="ALIMENTOS">ALIMENTOS</option>
                        <option value="TRANSPORTACION_TAXI">
                            TRANSPORTACION TERRESTRE (Taxi)
                        </option>
                        <option value="TRANSPORTACION_CASETAS">
                            TRANSPORTACION TERRESTRE (CASETAS)
                        </option>
                        <option value="TRANSPORTACION_AEREA">
                            TRANSPORTACION AEREA
                        </option>
                        <option value="VARIOS">
                            VARIOS (Herramienta o material)
                        </option>
                    </select>
                    <label class="form-label">PDF</label>
                    <input id="addPdf" type="file" accept="application/pdf" class="form-control mb-3">
                    <label class="form-label">XML</label>
                    <input id="addXml" type="file" accept=".xml" class="form-control mb-3">
                </div>
                <div id="addNoFactura" class="d-none">
                    <label class="form-label">Categoría</label>
                    <select id="addNoFacturaTipo" class="form-select mb-3">
                        <option value="HOTEL">HOTEL</option>
                        <option value="TRANSPORTACION_TERRESTRE_GENERAL">
                            TRANSPORTACION TERRESTRE (AUTOBUS, TAXI, ESTACIONAMIENTO)
                        </option>
                        <option value="ALIMENTOS">ALIMENTOS</option>
                        <option value="TRANSPORTACION_TAXI">
                            TRANSPORTACION TERRESTRE (Taxi)
                        </option>
                        <option value="TRANSPORTACION_CASETAS">
                            TRANSPORTACION TERRESTRE (CASETAS)
                        </option>
                        <option value="TRANSPORTACION_AEREA">
                            TRANSPORTACION AEREA
                        </option>
                        <option value="VARIOS">
                            VARIOS (Herramienta o material)
                        </option>
                    </select>
                    <label class="form-label">Fecha</label>
                    <input id="addNoFacturaFecha" type="date" class="form-control mb-3">
                    <label class="form-label">Costo</label>
                    <input id="addNoFacturaCosto" type="number" class="form-control mb-3" placeholder="0.00">
                    <label class="form-label">Compañía</label>
                    <input id="addNoFacturaCompany" type="text" class="form-control mb-3" placeholder="TREC9901285G3">
                    <label class="form-label">Justificación</label>
                    <textarea id="addNoFacturaJustified" class="form-control mb-3" rows="2" placeholder="Escriba su justificación de gasto"></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <div class="safe-area">
                    <button class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button class="btn btn-primary" onclick="guardarNuevoGasto()">Guardar</button>
                </div>
            </div>
        </div>
    </div>
</div>