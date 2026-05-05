<div class="modal fade" id="modalEditar" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-fullscreen-sm-down">
        <div class="modal-content">
            <div class="modal-header text-bg-primary">
                <h5 class="modal-title fw-bold">Editar gasto</h5>
                <button class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <input id="editIndex" type="hidden">
                <label class="form-label fw-semibold">Tipo de gasto</label>
                <select id="editTipo" class="form-select mb-3" disabled>
                    <option value="factura">Gastos con factura</option>
                    <option value="no_factura">Gastos sin factura</option>
                </select>
                <div id="editFactura" class="d-none">
                    <label class="form-label">Categoría</label>
                    <select id="editFacturaTipo" class="form-select mb-3">
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
                </div>
                <div id="editNoFactura" class="d-none">
                    <label class="form-label">Categoría</label>
                    <select id="editNoFacturaTipo" class="form-select mb-3">
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
                    <label class="form-label">Costo</label>
                    <input id="editNoFacturaCosto" type="number" class="form-control mb-3">
                </div>
            </div>
            <div class="modal-footer">
                <div class="safe-area">
                    <button class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button class="btn btn-primary" onclick="guardarEdicion()">Guardar cambios</button>
                </div>
            </div>
        </div>
    </div>
</div>