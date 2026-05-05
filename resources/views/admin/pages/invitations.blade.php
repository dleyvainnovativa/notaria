@extends('admin.memorial')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-2 pb-3">
    <div>
        <h3 id="main_title" class="display">Invitaciones</h3>
        <p class="text-muted mb-0">
            Administra y edita las invitaciones para el memorial
        </p>
    </div>
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#inviteModal">
        <span class="d-md-block d-none"><i class="fas fa-envelope me-2"></i> Invitar</span>
        <span class="d-md-none d-block"><i class="fas fa-envelope"></i></span>
    </button>
</div>
<div class="row g-4">
    <div class="col-12">
        <div class="table-responsive">
            <table id="invitations-table"
                class="table text-bg-dark card-dark border-dark"
                data-url="{{route('api.memorial.invitations', $memorial_slug)}}"
                data-pagination="true"
                data-classes="table"
                data-side-pagination="server"
                data-page-size="10"
                data-search="true"
                data-search-on-enter-key="false"
                data-show-refresh="true"
                data-ajax="ajaxRequest">
                <thead>
                    <tr>
                        <th data-field="name">Nombre</th>
                        <th data-field="permissions_count" data-sortable="true">Permisos</th>
                        <th data-visible="true" data-field="email">Correo</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td></td>
                        <td></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    <div class="col-auto text-end ms-auto">
        <a href="{{ route('admin.memorial.info', $memorial_slug) }}" class="">Ir a Información <i class="ms-2 fas fa-chevron-right"></i></a>
    </div>

</div>

<form class="needs-validation" id="invite-form" novalidate>
    <div
        class="modal fade"
        id="inviteModal"
        tabindex="-1"
        aria-labelledby="inviteModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-md modal-dialog-centered">
            <div class="modal-content p-2 card-dark border border-dark">

                <!-- Modal Header -->
                <div class="modal-header border-0">
                    <h5 class="modal-title text-dark mb-0 fw-bold" id="inviteModalLabel">
                        Invitar Persona
                    </h5>
                    <button type="button" class="btn ms-auto" data-bs-dismiss="modal" aria-label="Cerrar">
                        <i class="fas fa-xmark fa-lg text-dark"></i>
                    </button>
                </div>
                <!-- Modal Body -->
                <div class="modal-body text-dark">
                    <div class="row g-4">
                        <div class="col-12 pt-0 mt-0">
                            <small class="text-muted">Invita a las personas a unirse a tu memorial, asigna sus permisos para que sean parte de él.</small>
                        </div>
                        <div class="col-12">
                            <label for="email" class="form-label fw-semibold small text-dark">
                                Correo electrónico
                            </label>
                            <input
                                type="email"
                                class="text-dark form-control card-dark border border-dark"
                                id="invitation_email"
                                name="email"
                                placeholder="correo@ejemplo.com"
                                required>
                        </div>
                        <div class="col-12">
                            <div class="row">
                                <div class="col-auto">

                                    <label class="form-label fw-semibold small text-dark">
                                        Permisos
                                    </label>
                                </div>

                                <div class="col-auto ms-auto">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="perm_all">
                                        <label class="form-check-label small text-muted" for="perm_all">
                                            Seleccionar todos
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="row g-2 pt-2">
                                <div class="col-6">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="perm_info" name="permissions[info]">
                                        <label class="form-check-label small" for="perm_info">
                                            Información
                                        </label>
                                    </div>
                                </div>

                                <div class="col-6">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="perm_timeline" name="permissions[timeline]">
                                        <label class="form-check-label small" for="perm_timeline">
                                            Línea del tiempo
                                        </label>
                                    </div>
                                </div>

                                <div class="col-6">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="perm_life" name="permissions[life]">
                                        <label class="form-check-label small" for="perm_life">
                                            Historia de vida
                                        </label>
                                    </div>
                                </div>

                                <div class="col-6">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="perm_gallery" name="permissions[gallery]">
                                        <label class="form-check-label small" for="perm_gallery">
                                            Galería
                                        </label>
                                    </div>
                                </div>

                                <div class="col-6">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="perm_messages" name="permissions[messages]">
                                        <label class="form-check-label small" for="perm_messages">
                                            Mensajes
                                        </label>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="submit" class="btn btn-primary">Invitar</button>
                </div>
            </div>
        </div>
    </div>
</form>

<div class="modal fade" id="editInviteModal" tabindex="-1">
    <div class="modal-dialog modal-md modal-dialog-centered">
        <div class="modal-content p-2 card-dark border border-dark">

            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold text-dark">
                    Editar Permisos
                </h5>
                <button type="button" class="btn ms-auto" data-bs-dismiss="modal">
                    <i class="fas fa-xmark fa-lg text-dark"></i>
                </button>
            </div>

            <div class="modal-body text-dark pt-0">

                <input type="hidden" id="edit_invitation_id">

                <div class="mb-3">
                    <label class="form-label small fw-semibold">Correo</label>
                    <input type="text" id="edit_email" class="form-control" disabled>
                </div>

                <label class="form-label small fw-semibold">Permisos</label>

                <div class="row g-2">
                    <div class="col-6">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="edit_perm_info" name="permissions[info]">
                            <label class="form-check-label small" for="edit_perm_info">
                                Información
                            </label>
                        </div>
                    </div>

                    <div class="col-6">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="edit_perm_timeline" name="permissions[timeline]">
                            <label class="form-check-label small" for="edit_perm_timeline">
                                Línea del tiempo
                            </label>
                        </div>
                    </div>

                    <div class="col-6">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="edit_perm_life" name="permissions[life]">
                            <label class="form-check-label small" for="edit_perm_life">
                                Historia de vida
                            </label>
                        </div>
                    </div>

                    <div class="col-6">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="edit_perm_gallery" name="permissions[gallery]">
                            <label class="form-check-label small" for="edit_perm_gallery">
                                Galería
                            </label>
                        </div>
                    </div>

                    <div class="col-6">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="edit_perm_messages" name="permissions[messages]">
                            <label class="form-check-label small" for="edit_perm_messages">
                                Mensajes
                            </label>
                        </div>
                    </div>

                </div>

            </div>

            <div class="modal-footer border-0">
                <button class="btn btn-primary" id="edit_permissions_submit" onclick="editPermissions()">Guardar cambios</button>
            </div>

        </div>
    </div>
</div>
@vite(["resources/js/admin/invitations.js"])

@endsection