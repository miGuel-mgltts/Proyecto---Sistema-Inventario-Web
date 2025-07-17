// Barra lateral
const body = document.querySelector('body'),
      sidebar = body.querySelector('nav'),
      toggle = body.querySelector(".toggle"),
      frame = document.getElementById('content-frame');

// Toggle sidebar
toggle.addEventListener("click", () => { 
    sidebar.classList.toggle("close");
});

//alert
document.addEventListener('DOMContentLoaded', function() {
    const alert = document.querySelector('.alert-dismissible');
    if (alert) {
        setTimeout(() => {
            // Quitar clase 'show' para iniciar la transición
            alert.classList.remove('show');
            
            // Esperar la transición de Bootstrap (0.15s por defecto) antes de remover del DOM
            setTimeout(() => {
                alert.remove();
            }, 150); 
        }, 3000);
    }
});

//MODAL PRODUCTOS
document.addEventListener('DOMContentLoaded', function () {
    const botonesVerProducto = document.querySelectorAll('.btn-ver-producto');
    const modalProducto = new bootstrap.Modal(document.getElementById('verProductoModal'));

    botonesVerProducto.forEach(boton => {
        boton.addEventListener('click', () => {
            document.getElementById('modal-codigo').textContent = boton.getAttribute('data-codigo');
            document.getElementById('modal-nombre').textContent = boton.getAttribute('data-nombre');
            document.getElementById('modal-categoria').textContent = boton.getAttribute('data-categoria');
            document.getElementById('modal-precio-venta').textContent = boton.getAttribute('data-precio-venta');
            document.getElementById('modal-precio-compra').textContent = boton.getAttribute('data-precio-compra');
            document.getElementById('modal-stock').textContent = boton.getAttribute('data-stock');
            document.getElementById('modal-fecha').textContent = boton.getAttribute('data-fecha');
            document.getElementById('modal-origen').textContent = boton.getAttribute('data-origen');

            modalProducto.show();
        });
    });
});

// MODAL PROVEEDORES
document.addEventListener('DOMContentLoaded', function () {
    const botonesVerProveedor = document.querySelectorAll('.btn-ver-proveedor');
    const modalProveedor = new bootstrap.Modal(document.getElementById('verProveedorModal'));

    botonesVerProveedor.forEach(boton => {
        boton.addEventListener('click', () => {
            document.getElementById('modal-nombre-compania').textContent = boton.getAttribute('data-nombre-compania');
            document.getElementById('modal-nombre-contacto').textContent = boton.getAttribute('data-nombre-contacto');
            document.getElementById('modal-cedula').textContent = boton.getAttribute('data-cedula');
            document.getElementById('modal-telefono').textContent = boton.getAttribute('data-telefono');
            document.getElementById('modal-email').textContent = boton.getAttribute('data-email');
            document.getElementById('modal-fecha-integracion').textContent = boton.getAttribute('data-fecha-integracion');
            document.getElementById('modal-producto-relacionado').textContent = boton.getAttribute('data-producto-relacionado');
            document.getElementById('modal-tipo-contrato').textContent = boton.getAttribute('data-tipo-contrato');

            modalProveedor.show();
        });
    });
});

// MODAL DEVOLUCIONES
document.addEventListener('DOMContentLoaded', function () {
    const botonesVerDevolucion = document.querySelectorAll('.btn-ver-devolucion');
    const modalDevolucion = new bootstrap.Modal(document.getElementById('verDevolucionModal'));

    botonesVerDevolucion.forEach(boton => {
        boton.addEventListener('click', () => {
            document.getElementById('modal-codigo').textContent = boton.getAttribute('data-codigo');
            document.getElementById('modal-nombre-producto').textContent = boton.getAttribute('data-nombre-producto');
            document.getElementById('modal-cantidad').textContent = boton.getAttribute('data-cantidad');
            document.getElementById('modal-motivo').textContent = boton.getAttribute('data-motivo-nombre');
            document.getElementById('modal-fecha').textContent = boton.getAttribute('data-fecha');
            document.getElementById('modal-responsable').textContent = boton.getAttribute('data-responsable');

            modalDevolucion.show();
        });
    });
});

