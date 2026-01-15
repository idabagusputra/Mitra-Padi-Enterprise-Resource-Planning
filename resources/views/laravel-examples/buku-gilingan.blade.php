@extends('layouts.user_type.auth')

@section('content')

<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
<style>

    <!-- ============================================
   MODAL EDIT - STYLES
============================================ -->

/* Modal Overlay */
.modal-overlay {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.5);
    z-index: 9998;
    backdrop-filter: blur(4px);
}

.modal-overlay.active {
    display: block;
}

/* Modal Container */
.edit-modal {
    display: none;
    position: fixed;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    background: white;
    border-radius: 16px;
    box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
    z-index: 9999;
    max-width: 600px;
    width: 90%;
    max-height: 90vh;
    overflow-y: auto;
}

.edit-modal.active {
    display: block;
    animation: modalSlideIn 0.3s ease-out;
}

@keyframes modalSlideIn {
    from {
        opacity: 0;
        transform: translate(-50%, -45%);
    }
    to {
        opacity: 1;
        transform: translate(-50%, -50%);
    }
}

/* Modal Header */
.edit-modal-header {
    padding: 1.5rem;
    border-bottom: 2px solid #f1f3f5;
    display: flex;
    justify-content: space-between;
    align-items: center;
    background: linear-gradient(135deg, #fdf2f8 0%, #fce7f3 100%);
}

.edit-modal-title {
    font-size: 1.25rem;
    font-weight: 700;
    color: #cb0c9f;
    margin: 0;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.edit-modal-close {
    background: none;
    border: none;
    font-size: 1.5rem;
    color: #8392ab;
    cursor: pointer;
    padding: 0.25rem 0.5rem;
    transition: all 0.2s ease;
    border-radius: 8px;
}

.edit-modal-close:hover {
    background: rgba(203, 12, 159, 0.1);
    color: #cb0c9f;
}

/* Modal Body */
.edit-modal-body {
    padding: 1.5rem;
}

.edit-form-group {
    margin-bottom: 1.25rem;
}

.edit-form-label {
    display: block;
    font-size: 0.875rem;
    font-weight: 600;
    color: #344767;
    margin-bottom: 0.5rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.edit-form-control {
    width: 100%;
    padding: 0.75rem 1rem;
    border: 2px solid #e9ecef;
    border-radius: 10px;
    font-size: 0.9rem;
    transition: all 0.3s ease;
    background: #ffffff;
}

.edit-form-control:focus {
    outline: none;
    border-color: #cb0c9f;
    box-shadow: 0 0 0 3px rgba(203, 12, 159, 0.1);
}

.edit-form-control:disabled {
    background: #f8f9fa;
    cursor: not-allowed;
}

/* Modal Footer */
.edit-modal-footer {
    padding: 1.25rem 1.5rem;
    border-top: 2px solid #f1f3f5;
    display: flex;
    justify-content: flex-end;
    gap: 0.75rem;
    background: #fafbfc;
}

.edit-btn {
    padding: 0.75rem 1.5rem;
    border: none;
    border-radius: 10px;
    font-weight: 600;
    font-size: 0.875rem;
    cursor: pointer;
    transition: all 0.3s ease;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
}

.edit-btn-cancel {
    background: #e9ecef;
    color: #344767;
}

.edit-btn-cancel:hover {
    background: #dee2e6;
    transform: translateY(-1px);
}

.edit-btn-submit {
    background: linear-gradient(135deg, #cb0c9f 0%, #e91e8c 100%);
    color: white;
    box-shadow: 0 4px 15px rgba(203, 12, 159, 0.3);
}

.edit-btn-submit:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(203, 12, 159, 0.4);
}

/* Edit Button in Table */
.btn-edit {
    background: none;
    border: none;
    padding: 0;
    margin: 0;
    cursor: pointer;
    transition: all 0.2s ease;
    color: #e91e8c;
}

.btn-edit:hover {
    transform: scale(1.15);
    color: #cb0c9f;
}

/* Petani Dropdown in Modal */
.edit-petani-search-wrapper {
    position: relative;
}

.edit-petani-dropdown {
    position: absolute;
    top: 100%;
    left: 0;
    right: 0;
    background: white;
    border: 2px solid #cb0c9f;
    border-radius: 10px;
    margin-top: 4px;
    max-height: 200px;
    overflow-y: auto;
    z-index: 10000;
    box-shadow: 0 10px 30px rgba(203, 12, 159, 0.2);
    display: none;
}

.edit-petani-dropdown.show {
    display: block;
}

.edit-petani-dropdown-item {
    padding: 0.875rem 1rem;
    cursor: pointer;
    transition: all 0.2s ease;
    border-bottom: 1px solid #f1f3f5;
}

.edit-petani-dropdown-item:last-child {
    border-bottom: none;
}

.edit-petani-dropdown-item:hover {
    background: linear-gradient(135deg, #fdf2f8 0%, #fce7f3 100%);
}

.edit-petani-dropdown-item .petani-name {
    font-weight: 600;
    color: #cb0c9f;
    font-size: 0.875rem;
}

.edit-petani-dropdown-item .petani-info {
    color: #8392ab;
    font-size: 0.75rem;
}

/* Scrollbar untuk modal */
.edit-modal::-webkit-scrollbar {
    width: 8px;
}

.edit-modal::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 4px;
}

.edit-modal::-webkit-scrollbar-thumb {
    background: linear-gradient(180deg, #cb0c9f 0%, #e91e8c 100%);
    border-radius: 4px;
}

.edit-petani-dropdown::-webkit-scrollbar {
    width: 6px;
}

.edit-petani-dropdown::-webkit-scrollbar-track {
    background: #f8f9fa;
}

.edit-petani-dropdown::-webkit-scrollbar-thumb {
    background: linear-gradient(180deg, #cb0c9f 0%, #e91e8c 100%);
    border-radius: 3px;
}

/* Responsive */
@media (max-width: 768px) {
    .edit-modal {
        width: 95%;
        max-height: 85vh;
    }

    .edit-modal-header {
        padding: 1rem;
    }

    .edit-modal-body {
        padding: 1rem;
    }

    .edit-modal-footer {
        flex-direction: column;
        padding: 1rem;
    }

    .edit-btn {
        width: 100%;
        justify-content: center;
    }
}



    /* ============================================
       BASE STYLES - Modern & Clean
    ============================================ */
    body {
        overflow-x: hidden;
        background-color: #f8f9fa;
    }

    .card {
        border: none;
        border-radius: 16px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        overflow: hidden;
    }

    .card-header {
        background: #ffffff;
        border-bottom: 1px solid #f1f3f5;
    }

    /* ============================================
   STOK GLOBAL - Compact Horizontal Cards
============================================ */
.stok-global-section {
    padding: 1rem 1.5rem;
}

.stok-cards-container {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
    gap: 0.75rem;
}

.stok-card {
    background: #ffffff;
    border-radius: 12px;
    padding: 0.75rem 1rem;
    border: 1px solid #e9ecef;
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
}

.stok-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    width: 4px;
    height: 100%;
    border-radius: 12px 0 0 12px;
}

.stok-card.beras::before {
    background: linear-gradient(180deg, #17ad37 0%, #98ec2d 100%);
}

.stok-card.konga::before {
    background: linear-gradient(180deg, #2152ff 0%, #21d4fd 100%);
}

.stok-card.menir::before {
    background: linear-gradient(180deg, #f5365c 0%, #f56036 100%);
}

.stok-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(0, 0, 0, 0.08);
}

/* Horizontal Layout: Icon + Info side by side */
.stok-card-content {
    display: flex;
    align-items: center;
    gap: 0.875rem;
}

.stok-card .stok-icon {
    width: 42px;
    height: 42px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}

.stok-card.beras .stok-icon {
    background: linear-gradient(135deg, rgba(23, 173, 55, 0.1) 0%, rgba(152, 236, 45, 0.1) 100%);
}

.stok-card.konga .stok-icon {
    background: linear-gradient(135deg, rgba(33, 82, 255, 0.1) 0%, rgba(33, 212, 253, 0.1) 100%);
}

.stok-card.menir .stok-icon {
    background: linear-gradient(135deg, rgba(245, 54, 92, 0.1) 0%, rgba(245, 96, 54, 0.1) 100%);
}

.stok-card .stok-icon i {
    font-size: 1.25rem;
}

.stok-card.beras .stok-icon i {
    color: #17ad37;
}

.stok-card.konga .stok-icon i {
    color: #2152ff;
}

.stok-card.menir .stok-icon i {
    color: #f5365c;
}

/* Info section */
.stok-card .stok-info {
    flex: 1;
    min-width: 0;
}

.stok-card .stok-label {
    font-size: 0.7rem;
    font-weight: 600;
    color: #8392ab;
    text-transform: uppercase;
    letter-spacing: 0.4px;
    margin-bottom: 0.125rem;
    line-height: 1.2;
}

.stok-card .stok-value {
    font-size: 1.25rem;
    font-weight: 700;
    color: #344767;
    line-height: 1.2;
}

.stok-card .stok-unit {
    font-size: 0.75rem;
    font-weight: 500;
    color: #8392ab;
    margin-left: 0.15rem;
}

/* Responsive */
@media (max-width: 992px) {
    .stok-cards-container {
        grid-template-columns: repeat(3, 1fr);
    }
}

@media (max-width: 768px) {
    .stok-cards-container {
        grid-template-columns: 1fr;
    }

    .stok-global-section {
        padding: 0.75rem 1rem;
    }
}

@media (max-width: 576px) {
    .stok-card .stok-value {
        font-size: 1.1rem;
    }

    .stok-card .stok-icon {
        width: 36px;
        height: 36px;
    }

    .stok-card .stok-icon i {
        font-size: 1rem;
    }
}

    /* ============================================
       SEARCH PETANI GLOBAL - Enhanced
    ============================================ */
    .search-petani-global {
        padding: 1.5rem 0rem 1.5rem;
    }

    .search-petani-wrapper {
        position: relative;
        max-width: 400px;
    }

    .search-petani-wrapper .form-control {
        padding-left: 3rem;
        height: 48px;
        border-radius: 12px;
        border: 2px solid #e9ecef;
        font-size: 0.9rem;
        transition: all 0.3s ease;
        background: #ffffff;
    }

    .search-petani-wrapper .form-control:focus {
        border-color: #cb0c9f;
        box-shadow: 0 0 0 3px rgba(203, 12, 159, 0.1);
    }

    .search-petani-wrapper .search-icon {
        position: absolute;
        left: 1rem;
        top: 50%;
        transform: translateY(-50%);
        color: #8392ab;
        font-size: 1.1rem;
        z-index: 5;
    }

    .search-petani-results {
        position: absolute;
        top: 100%;
        left: 0;
        right: 0;
        background: #ffffff;
        border: 2px solid #cb0c9f;
        border-radius: 12px;
        margin-top: 8px;
        max-height: 280px;
        overflow-y: auto;
        overflow-x: hidden;
        z-index: 1000;
        box-shadow: 0 10px 40px rgba(203, 12, 159, 0.15);
        display: none;
        /* Improved scrolling */
        -webkit-overflow-scrolling: touch;
        overscroll-behavior: contain;
        scroll-behavior: smooth;
    }

    .search-petani-results::-webkit-scrollbar {
        width: 6px;
    }

    .search-petani-results::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 3px;
    }

    .search-petani-results::-webkit-scrollbar-thumb {
        background: linear-gradient(180deg, #cb0c9f 0%, #e91e8c 100%);
        border-radius: 3px;
    }

    .search-petani-item {
        padding: 1rem 1.25rem;
        cursor: pointer;
        transition: all 0.2s ease;
        border-bottom: 1px solid #f1f3f5;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    /* Badge untuk menampilkan info pinjaman - Pink Theme */
.petani-name .pinjaman-badge {
    display: inline-block;
    margin-left: 0px;
    padding: 4px 10px;
    font-size: 0.7rem;
    font-weight: 600;
    color: #cb0c9f;
    background: linear-gradient(135deg, #fce7f3 0%, #fdf2f8 100%);
    border: 1px solid rgba(203, 12, 159, 0.15);
    border-radius: 6px;
    white-space: nowrap;
    transition: all 0.2s ease;
}

.petani-name .pinjaman-badge:hover {
    background: linear-gradient(135deg, #fbcfe8 0%, #fce7f3 100%);
    border-color: rgba(203, 12, 159, 0.25);
}

.petani-name {
    display: flex;
    align-items: center;
    flex-wrap: wrap;
    gap: 4px;
    line-height: 1.4;
}

/* Responsive untuk mobile */
@media (max-width: 576px) {
    .petani-name .pinjaman-badge {
        display: block;
        margin-left: 0;
        margin-top: 4px;
        font-size: 0.65rem;
        width: 100%;
    }
}

    .search-petani-item:last-child {
        border-bottom: none;
    }

    .search-petani-item:hover {
        background: linear-gradient(135deg, #fdf2f8 0%, #fce7f3 100%);
    }

    .search-petani-item .petani-avatar {
        width: 40px;
        height: 40px;
        border-radius: 10px;
        background: linear-gradient(135deg, #cb0c9f 0%, #e91e8c 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        color: #ffffff;
        font-weight: 600;
        font-size: 0.9rem;
        flex-shrink: 0;
    }

    .search-petani-item .petani-info {
        flex: 1;
        min-width: 0;
    }

    .search-petani-item .petani-name {
        font-weight: 600;
        color: #344767;
        font-size: 0.9rem;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }



    .search-petani-item .petani-alamat {
        font-size: 0.8rem;
        color: #8392ab;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    /* ============================================
   SEARCH + FILTER CONTAINER
============================================ */
.search-filter-container {
    display: flex;
    align-items: center;
    gap: 1rem;
    width: 100%;
}

.search-filter-container .search-petani-wrapper {
    flex: 1;
    max-width: none;
}

.status-filter-wrapper {
    flex-shrink: 0;
    min-width: 160px;
}

.status-filter-wrapper .form-select {
    height: 48px;
    border-radius: 12px;
    border: 2px solid #e9ecef;
    font-size: 0.9rem;
    font-weight: 500;
    color: #344767;
    padding: 0 2.5rem 0 1rem;
    background-color: #ffffff;
    cursor: pointer;
    transition: all 0.3s ease;
}

.status-filter-wrapper .form-select:focus {
    border-color: #cb0c9f;
    box-shadow: 0 0 0 3px rgba(203, 12, 159, 0.1);
    outline: none;
}

.status-filter-wrapper .form-select option {
    padding: 0.5rem;
    font-weight: 500;
}

@media (max-width: 576px) {
    .search-filter-container {
        flex-direction: column;
        gap: 0.75rem;
    }

    .status-filter-wrapper {
        width: 100%;
    }
}

    /* ============================================
       TAB NAVIGATION - Elegant & Modern
    ============================================ */
    .nav-tabs-wrapper {
        background: #ffffff;
        border-radius: 0;
        margin: 0;
        padding: 0;
        border-bottom: 2px solid #f1f3f5;
        overflow-x: auto;
        overflow-y: hidden;
        -webkit-overflow-scrolling: touch;
    }

    .nav-tabs-wrapper::-webkit-scrollbar {
        height: 0;
        display: none;
    }

    .nav-tabs {
        display: flex;
        flex-wrap: nowrap;
        border: none;
        margin: 0;
        padding: 0;
        min-width: max-content;
        background: transparent;
    }

    .nav-tabs .nav-item {
        margin: 0;
        flex-shrink: 0;
    }

    .nav-tabs .nav-link {
        border: none;
        border-radius: 0;
        color: #8392ab;
        font-weight: 600;
        padding: 1rem 1.5rem;
        font-size: 0.875rem;
        letter-spacing: 0.3px;
        background: transparent;
        position: relative;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        white-space: nowrap;
        transition: all 0.3s ease;
    }

    .nav-tabs .nav-link .tab-icon {
        width: 28px;
        height: 28px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.9rem;
        background: #f1f3f5;
        transition: all 0.3s ease;
    }

    .nav-tabs .nav-link:hover {
        color: #cb0c9f;
        background: rgba(203, 12, 159, 0.04);
    }

    .nav-tabs .nav-link:hover .tab-icon {
        background: rgba(203, 12, 159, 0.1);
    }

    .nav-tabs .nav-link.active {
        color: #cb0c9f;
        background: rgba(203, 12, 159, 0.04);
        font-weight: 700;
    }

    .nav-tabs .nav-link.active::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        height: 3px;
        background: linear-gradient(90deg, #cb0c9f 0%, #e91e8c 100%);
        border-radius: 3px 3px 0 0;
    }

    .nav-tabs .nav-link.active .tab-icon {
        background: linear-gradient(135deg, #cb0c9f 0%, #e91e8c 100%);
        color: #ffffff;
        box-shadow: 0 4px 12px rgba(203, 12, 159, 0.3);
    }

    /* ============================================
       TAB CONTENT - Clean Container
    ============================================ */
    .tab-content {
        padding: 1.5rem 0rem 1.5rem;
        background: #ffffff;
    }

    .tab-pane {
        animation: fadeIn 0.3s ease;
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }

    /* ============================================
       INPUT TABLE - Matching Template Colors
    ============================================ */
    .input-table {
        background: #ffffff;
        border-radius: 14px;
        overflow: hidden;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
        border: 1px solid #e9ecef;
        margin-bottom: 1.5rem;
    }

    .input-table table {
        margin: 0;
    }

    .input-table thead th {
        background: linear-gradient(135deg, #cb0c9f 0%, #e91e8c 100%);
        color: #ffffff;
        font-weight: 600;
        font-size: 0.8rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        padding: 1rem 0.75rem;
        border: none;
        white-space: nowrap;
    }

    .input-row {
        background: #fafbfc;
    }

    .input-row td {
        padding: 0.75rem;
        vertical-align: middle;
        border-bottom: 1px solid #f1f3f5;
    }

    .dynamic-row td {
        padding: 0.75rem;
        vertical-align: middle;
        background: #ffffff;
        border-bottom: 1px solid #f1f3f5;
    }

    /* ============================================
       FORM CONTROLS - Enhanced Styling
    ============================================ */
    .form-control,
    .form-select {
        border: 2px solid #e9ecef;
        border-radius: 8px;
        padding: 0.5rem 0.75rem;
        font-size: 0.875rem;
        transition: all 0.2s ease;
        height: auto;
        background: #ffffff;
    }

    .form-control:focus,
    .form-select:focus {
        border-color: #cb0c9f;
        box-shadow: 0 0 0 3px rgba(203, 12, 159, 0.1);
        outline: none;
    }

    .form-control::placeholder {
        color: #adb5bd;
    }

    /* ============================================
   PETANI DROPDOWN - Fixed Scroll & Clear Display
============================================ */
.petani-dropdown {
    position: absolute;
    top: 100%;
    left: 0;
    right: 0;
    background: #ffffff;
    border: 2px solid #cb0c9f;
    border-radius: 12px;
    max-height: 280px;
    min-width: 280px;
    width: 100%;
    overflow-y: auto;
    overflow-x: hidden;
    z-index: 1050;
    box-shadow: 0 10px 40px rgba(203, 12, 159, 0.15);
    margin-top: 4px;
    /* Critical: Enable smooth scroll */
    -webkit-overflow-scrolling: touch;
    overscroll-behavior: contain;
    scroll-behavior: smooth;
}

/* Scrollbar styling */
.petani-dropdown::-webkit-scrollbar {
    width: 8px;
}

.petani-dropdown::-webkit-scrollbar-track {
    background: #f8f9fa;
    border-radius: 0 12px 12px 0;
}

.petani-dropdown::-webkit-scrollbar-thumb {
    background: linear-gradient(180deg, #cb0c9f 0%, #e91e8c 100%);
    border-radius: 4px;
}

.petani-dropdown::-webkit-scrollbar-thumb:hover {
    background: linear-gradient(180deg, #a00a7f 0%, #c9186f 100%);
}

.petani-dropdown-item {
    padding: 0.875rem 1rem;
    cursor: pointer;
    transition: all 0.2s ease;
    border-bottom: 1px solid #f1f3f5;
    display: flex;
    flex-direction: column;
    gap: 0.25rem;
}

.petani-dropdown-item:last-child {
    border-bottom: none;
}

.petani-dropdown-item:hover {
    background: linear-gradient(135deg, #fdf2f8 0%, #fce7f3 100%);
}

.petani-dropdown-item .petani-name {
    font-weight: 600;
    color: #cb0c9f;
    font-size: 0.875rem;
    line-height: 1.3;
}

.petani-dropdown-item .petani-info {
    color: #8392ab;
    font-size: 0.75rem;
    line-height: 1.2;
}

    /* ============================================
       REMOVE ROW BUTTON
    ============================================ */
    .remove-row-btn {
        width: 32px;
        height: 32px;
        background: linear-gradient(135deg, #f5365c 0%, #f56036 100%);
        border: none;
        color: white;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 8px;
        padding: 0;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .remove-row-btn:hover {
        transform: scale(1.1);
        box-shadow: 0 4px 15px rgba(245, 54, 92, 0.4);
    }

    /* ============================================
       SUBMIT SECTION
    ============================================ */
    .submit-section {
        padding: 1rem 1.25rem;
        background: #fafbfc;
        border-top: 1px solid #f1f3f5;
    }

    .btn-submit-all {
        background: linear-gradient(135deg, #cb0c9f 0%, #e91e8c 100%);
        border: none;
        color: white;
        padding: 0.875rem 2rem;
        font-weight: 600;
        font-size: 0.9rem;
        border-radius: 10px;
        width: 100%;
        transition: all 0.3s ease;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        box-shadow: 0 4px 15px rgba(203, 12, 159, 0.3);
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
    }

    .btn-submit-all:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(203, 12, 159, 0.4);
        color: white;
    }

    /* ============================================
       DATA TABLE - Clean & Professional
    ============================================ */
    .data-table {
        border-radius: 14px;
        overflow: hidden;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.04);
    }

    .data-table thead th {
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        color: #344767;
        font-weight: 600;
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        padding: 1rem 0.75rem;
        border-bottom: 2px solid #e9ecef;
        white-space: nowrap;
    }

    .data-table tbody tr {
        transition: all 0.2s ease;
    }

    .data-table tbody tr:hover {
        background: linear-gradient(135deg, #fdf2f8 0%, #fef7ff 100%);
    }

    .data-table tbody td {
        padding: 0.875rem 0.75rem;
        vertical-align: middle;
        border-bottom: 1px solid #f1f3f5;
        font-size: 0.85rem;
    }

    .data-table .text-xs {
        font-size: 0.8rem;
        color: #344767;
    }





.btn-edit i,
.btn-link i {
    vertical-align: middle;
}





    /* ============================================
       BADGE STYLES - Matching Template
    ============================================ */
    .badge {
        padding: 0.5rem 0.875rem;
        font-weight: 600;
        font-size: 0.7rem;
        border-radius: 6px;
        letter-spacing: 0.3px;
    }

    .bg-gradient-success {
        background: linear-gradient(135deg, #17ad37 0%, #98ec2d 100%);
    }

    .bg-gradient-warning {
        background: linear-gradient(135deg, #f5365c 0%, #f56036 100%);
    }

    .bg-gradient-info {
        background: linear-gradient(135deg, #2152ff 0%, #21d4fd 100%);
    }

    .bg-gradient-secondary {
        background: linear-gradient(135deg, #627594 0%, #a8b8d8 100%);
    }

    /* ============================================
       ACTION BUTTONS
    ============================================ */
    .btn-link {
        transition: all 0.2s ease;
    }

    .btn-link:hover {
        transform: scale(1.15);
    }

    .btn-link.text-danger:hover {
        color: #f5365c !important;
    }

    /* ============================================
       SCROLLBAR STYLING
    ============================================ */
    .table-responsive {
        border-radius: 14px;
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
    }

    .table-responsive::-webkit-scrollbar {
        height: 8px;
    }

    .table-responsive::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 4px;
    }

    .table-responsive::-webkit-scrollbar-thumb {
        background: linear-gradient(90deg, #cb0c9f 0%, #e91e8c 100%);
        border-radius: 4px;
    }

    /* ============================================
   PETANI INPUT WRAPPER
============================================ */
.petani-input-wrapper {
    position: relative;
    width: 100%;
}

/* Ensure dropdown doesn't get cut off */
.input-table {
    overflow: visible !important;
}

.input-table table {
    overflow: visible;
}

.input-table tbody {
    overflow: visible;
}

.dynamic-row td:first-child {
    overflow: visible;
}

    /* ============================================
       RESPONSIVE STYLES
    ============================================ */
    @media (max-width: 992px) {
        .stok-cards-container {
            grid-template-columns: repeat(3, 1fr);
        }
    }

    @media (max-width: 768px) {
        .nav-tabs .nav-link {
            padding: 0.75rem 1rem;
            font-size: 0.8rem;
        }

        .nav-tabs .nav-link .tab-icon {
            width: 24px;
            height: 24px;
            font-size: 0.8rem;
        }

        .stok-cards-container {
            grid-template-columns: 1fr;
        }

        .stok-global-section,
        .search-petani-global {
            padding: 1rem;
        }

        .tab-content {
            padding: 1rem;
        }

        .table {
            font-size: 0.75rem;
        }

        .search-petani-wrapper {
            max-width: 100%;
        }
    }

    @media (max-width: 576px) {
        .nav-tabs .nav-link span:not(.tab-icon) {
            display: none;
        }

        .nav-tabs .nav-link {
            padding: 0.75rem;
        }

        .stok-card .stok-value {
            font-size: 1.25rem;
        }
    }
</style>

<div>
    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <!-- Header -->
                {{-- <div class="card-header pb-0 p-4">
                    <div class="d-flex align-items-center gap-3">
                        <div class="icon-wrapper" style="width: 48px; height: 48px; border-radius: 12px; background: linear-gradient(135deg, #cb0c9f 0%, #e91e8c 100%); display: flex; align-items: center; justify-content: center; box-shadow: 0 4px 15px rgba(203, 12, 159, 0.3);">
                            <i class="bi bi-journal-bookmark-fill text-white" style="font-size: 1.25rem;"></i>
                        </div>
                        <div>
                            <h4 class="mb-0" style="color: #344767; font-weight: 700;">Manajemen Buku Stok</h4>
                            <p class="text-sm text-muted mb-0">Kelola stok beras, konga & menir</p>
                        </div>
                    </div>
                </div> --}}

                <!-- Stok Global Section -->
                <div class="stok-global-section">
                    {{-- <div class="stok-global-header">
                        <div class="icon-wrapper">
                            <i class="bi bi-box-seam-fill"></i>
                        </div>
                        <h6>Stok Global</h6>
                    </div> --}}
                    <div class="stok-cards-container">
    <div class="stok-card beras">
        <div class="stok-card-content">
            <div class="stok-icon">
                <i class="bi bi-droplet-fill"></i>
            </div>
            <div class="stok-info">
                <div class="stok-label">Stok Beras</div>
                <div class="stok-value">
                    {{ number_format($stokGlobal->stok_beras ?? 0, 2, ',', '.') }}
                    <span class="stok-unit">Kg</span>
                </div>
            </div>
        </div>
    </div>
    <div class="stok-card konga">
        <div class="stok-card-content">
            <div class="stok-icon">
                <i class="bi bi-circle-fill"></i>
            </div>
            <div class="stok-info">
                <div class="stok-label">Stok Konga</div>
                <div class="stok-value">
                    {{ number_format($stokGlobal->stok_konga ?? 0, 2, ',', '.') }}
                    <span class="stok-unit">Karung</span>
                </div>
            </div>
        </div>
    </div>
    <div class="stok-card menir">
        <div class="stok-card-content">
            <div class="stok-icon">
                <i class="bi bi-diamond-fill"></i>
            </div>
            <div class="stok-info">
                <div class="stok-label">Stok Menir</div>
                <div class="stok-value">
                    {{ number_format($stokGlobal->stok_menir ?? 0, 2, ',', '.') }}
                    <span class="stok-unit">Kg</span>
                </div>
            </div>
        </div>
    </div>
</div>

                <!-- Search Petani Global + Filter Status -->
<div class="search-petani-global">
    <div class="search-filter-container">
        <div class="search-petani-wrapper">
            <i class="bi bi-search search-icon"></i>
            <input type="text" id="search-petani-global" class="form-control"
                   placeholder="Cari petani untuk melihat data..." autocomplete="off">
            <div class="search-petani-results" id="search-petani-results"></div>
        </div>
        <div class="status-filter-wrapper">
            <select id="filter-status-global" class="form-select">
                <option value="">Semua Status</option>
                <option value="1">Lunas</option>
                <option value="0">Belum Lunas</option>
            </select>
        </div>
    </div>
</div>

                <!-- Tab Navigation -->
                <div class="nav-tabs-wrapper">
                    <ul class="nav nav-tabs" id="bukuStokTabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="buku-beras-tab" data-bs-toggle="tab"
                                    data-bs-target="#buku-beras" type="button" role="tab">
                                <span class="tab-icon"><i class="bi bi-droplet-fill"></i></span>
                                <span>Buku Beras</span>
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="pinjaman-beras-tab" data-bs-toggle="tab"
                                    data-bs-target="#pinjaman-beras" type="button" role="tab">
                                <span class="tab-icon"><i class="bi bi-hand-index-fill"></i></span>
                                <span>Pinjaman Beras</span>
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="penjualan-beras-tab" data-bs-toggle="tab"
                                    data-bs-target="#penjualan-beras" type="button" role="tab">
                                <span class="tab-icon"><i class="bi bi-cash-stack"></i></span>
                                <span>Penjualan Beras</span>
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="buku-konga-tab" data-bs-toggle="tab"
                                    data-bs-target="#buku-konga" type="button" role="tab">
                                <span class="tab-icon"><i class="bi bi-circle-fill"></i></span>
                                <span>Buku Konga & Menir</span>
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="buku-pinjaman-konga-tab" data-bs-toggle="tab"
                                    data-bs-target="#pinjaman-konga" type="button" role="tab">
                                <span class="tab-icon"><i class="bi bi-arrow-left-right"></i></span>
                                <span>Pinjaman Konga</span>
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="penjualan-konga-tab" data-bs-toggle="tab"
                                    data-bs-target="#penjualan-konga" type="button" role="tab">
                                <span class="tab-icon"><i class="bi bi-currency-dollar"></i></span>
                                <span>Penjualan Konga & Menir</span>
                            </button>
                        </li>
                    </ul>
                </div>

                <!-- Tab Content -->
                <div class="tab-content" id="bukuStokTabContent">

                    <!-- Buku Beras Tab -->
                    <div class="tab-pane fade show active" id="buku-beras" role="tabpanel">
                        <form id="form-buku-beras" class="mb-4">
                            @csrf
                            <div class="input-table">
                                <table class="table mb-0">
                                    <thead>
                                        <tr>
                                            <th style="width: 25%;">Petani</th>
                                            <th class="text-center" style="width: 15%;">Jemur</th>
                                            <th class="text-center" style="width: 15%;">Giling Kotor</th>
                                            <th class="text-center" style="width: 15%;">Beras Pulang</th>
                                            <th class="text-center" style="width: 15%;">Status</th>
                                            <th class="text-center" style="width: 15%;">Tanggal</th>
                                            <th class="text-center" style="width: 6%;">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody id="beras-input-rows">
                                        <tr class="input-row dynamic-row" data-row="0">
                                            <td>
    <div class="petani-input-wrapper">
        <input type="text" class="form-control form-control-sm petani-search"
               data-target="beras" data-row="0" placeholder="Cari petani..."
               autocomplete="off" required>
        <input type="hidden" name="rows[0][petani_id]" class="petani-id">
        <div class="petani-dropdown"></div>
    </div>
</td>
<td><input type="text" class="form-control form-control-sm number-format" name="rows[0][jemur]" placeholder="0" inputmode="decimal" required></td>
<td><input type="text" class="form-control form-control-sm number-format" name="rows[0][giling_kotor]" placeholder="0" inputmode="decimal" required></td>
<td><input type="text" class="form-control form-control-sm number-format" name="rows[0][beras_pulang]" placeholder="0" inputmode="decimal"></td>
<td>
    <select class="form-select form-select-sm" name="rows[0][status]" required>
        <option value="0">BELUM LUNAS</option>
        <option value="1">LUNAS</option>
    </select>
</td>
<td><input type="date" class="form-control form-control-sm" name="rows[0][tanggal]" value="{{ date('Y-m-d') }}" required></td>
                                            <td class="text-center">
                                                <button type="button" class="remove-row-btn" onclick="removeRow(this)" style="visibility: hidden;">
                                                    <i class="bi bi-x-lg"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                                <div class="submit-section">
                                    <button type="submit" class="btn-submit-all">
                                        <i class="bi bi-check-circle-fill"></i>
                                        Simpan Semua Data
                                    </button>
                                </div>
                            </div>
                        </form>

                        <!-- Data Section -->
                        <div class="table-responsive">
                            <table class="table data-table mb-0">
                                <thead>
                                    <tr>
                                        {{-- <th class="text-center">ID</th> --}}
                                        <th class="text-center">Tanggal</th>
                                        <th>Petani</th>
                                        <th class="text-center">Jemur</th>
                                        <th class="text-center">Giling Kotor</th>
                                        <th class="text-center">Ongkos</th>
                                        <th class="text-center">Beras Pinjam</th>
                                        <th class="text-center">Beras Bersih</th>
                                        <th class="text-center">Beras Pulang</th>
                                        <th class="text-center">Jual</th>
                                        <th class="text-center">Masuk Stok</th>
                                        <th class="text-center">Harga</th>
                                        <th class="text-center">Status</th>
                                        <th class="text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($bukuStokBeras as $item)
                                    {{-- <tr data-petani-id="{{ $item->petani_id }}" data-status="{{ $item->status ? '1' : '0' }}"> --}}
                                        <td class="text-center"><span class="text-xs fw-bold">{{ $item->id }}</span></td>
                                        <td class="text-center"><span class="text-xs">{{ $item->tanggal ? $item->tanggal->format('d-m-Y') : '-' }}</span></td>
                                        <td><span class="text-xs">{{ $item->nama_petani }}</span></td>
                                        <td class="text-center"><span class="text-xs">{{ number_format($item->jemur ?? 0, 2, ',', '.') }}</span></td>
                                        <td class="text-center"><span class="text-xs">{{ number_format($item->giling_kotor ?? 0, 2, ',', '.') }}</span></td>
                                        <td class="text-center"><span class="text-xs">{{ number_format($item->ongkos ?? 0, 2, ',', '.') }}</span></td>
                                        <td class="text-center"><span class="text-xs">{{ number_format($item->pinjaman_beras ?? 0, 2, ',', '.') }}</span></td>
                                        <td class="text-center"><span class="text-xs">{{ number_format($item->beras_bersih ?? 0, 2, ',', '.') }}</span></td>
                                        <td class="text-center"><span class="text-xs">{{ number_format($item->beras_pulang ?? 0, 2, ',', '.') }}</span></td>
                                        <td class="text-center"><span class="text-xs">{{ number_format($item->jual ?? 0, 2, ',', '.') }}</span></td>
                                        <td class="text-center"><span class="text-xs">{{ number_format($item->jual_kotor ?? 0, 2, ',', '.') }}</span></td>
                                        <td class="text-center"><span class="text-xs">Rp {{ number_format($item->harga ?? 0, 2, ',', '.') }}</span></td>
                                        <td class="text-center">
                                            <span class="badge bg-gradient-{{ $item->status ? 'success' : 'secondary' }}">
                                                {{ $item->status ? 'LUNAS' : 'BELUM LUNAS' }}
                                            </span>
                                        </td>
                                        <td class="text-center">
    <button type="button" class="btn-edit me-3" onclick="openEditModal('buku-beras', {
        id: {{ $item->id }},
        tanggal: '{{ $item->tanggal ? $item->tanggal->format('Y-m-d') : '' }}',
        petani_id: {{ $item->petani_id }},
        nama_petani: '{{ addslashes($item->nama_petani) }}',
        jemur: {{ $item->jemur ?? 0 }},
        giling_kotor: {{ $item->giling_kotor ?? 0 }},
        beras_pulang: {{ $item->beras_pulang ?? 0 }},
        status: {{ $item->status ? 1 : 0 }}
    })">
        <i class="bi bi-pencil-square fs-5"></i>
    </button>
    <form action="{{ route('buku-stok-beras.destroy', $item->id) }}" method="POST" class="d-inline delete-form">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn btn-link text-danger p-0 m-0">
            <i class="bi bi-trash3-fill fs-5"></i>
        </button>
    </form>
</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Pinjaman Beras Tab -->
                    <div class="tab-pane fade" id="pinjaman-beras" role="tabpanel">
                        <form id="form-pinjaman-beras" class="mb-4">
                            @csrf
                            <div class="input-table">
                                <table class="table mb-0">
                                    <thead>
                                        <tr>
                                            <th style="width: 35%;">Petani</th>
                                            <th class="text-center" style="width: 25%;">Jumlah Pinjaman</th>
                                            <th class="text-center" style="width: 25%;">Tanggal</th>
                                            <th class="text-center" style="width: 6%;">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody id="pinjaman-input-rows">
                                        <tr class="input-row dynamic-row" data-row="0">
                                            <td>
                                                <div style="position: relative;">
                                                    <input type="text" class="form-control form-control-sm petani-search"
                                                           data-target="pinjaman" data-row="0" placeholder="Cari petani..." required>
                                                    <input type="hidden" name="rows[0][petani_id]" class="petani-id">
                                                    <div class="petani-dropdown" style="display:none;"></div>
                                                </div>
                                            </td>
                                            <td><input type="text" class="form-control form-control-sm number-format" name="rows[0][jumlah]" placeholder="0" inputmode="decimal" required></td>
                                            <td><input type="date" class="form-control form-control-sm" name="rows[0][tanggal]" value="{{ date('Y-m-d') }}" required></td>
                                            <td class="text-center">
                                                <button type="button" class="remove-row-btn" onclick="removeRow(this)" style="visibility: hidden;">
                                                    <i class="bi bi-x-lg"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                                <div class="submit-section">
                                    <button type="submit" class="btn-submit-all">
                                        <i class="bi bi-check-circle-fill"></i>
                                        Simpan Semua Data
                                    </button>
                                </div>
                            </div>
                        </form>

                        <!-- Data Section -->
                        <div class="table-responsive">
                            <table class="table data-table mb-0">
                                <thead>
                                    <tr>
                                        {{-- <th class="text-center">ID</th> --}}
                                        <th class="text-center">Tanggal</th>
                                        <th>Petani</th>
                                        <th class="text-center">Jumlah</th>
                                        <th class="text-center">Status</th>
                                        <th class="text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($pinjamanBeras as $item)
                                    {{-- <tr data-petani-id="{{ $item->petani_id }}" data-status="{{ $item->status ? '1' : '0' }}"> --}}
                                        <td class="text-center"><span class="text-xs fw-bold">{{ $item->id }}</span></td>
                                        <td class="text-center"><span class="text-xs">{{ $item->tanggal ? $item->tanggal->format('d-m-Y') : '-' }}</span></td>
                                        <td><span class="text-xs">{{ $item->nama_petani }}</span></td>
                                        <td class="text-center"><span class="text-xs">{{ number_format($item->jumlah ?? 0, 2, ',', '.') }}</span></td>
                                        <td class="text-center">
                                            <span class="badge bg-gradient-{{ $item->status ? 'success' : 'warning' }}">
                                                {{ $item->status ? 'LUNAS' : 'BELUM LUNAS' }}
                                            </span>
                                        </td>
                                        <td class="text-center">
    @if($item->status == 0)
    <button type="button" class="btn-edit me-3" onclick="openEditModal('pinjaman-beras', {
        id: {{ $item->id }},
        tanggal: '{{ $item->tanggal ? $item->tanggal->format('Y-m-d') : '' }}',
        petani_id: {{ $item->petani_id }},
        nama_petani: '{{ addslashes($item->nama_petani) }}',
        jumlah: {{ $item->jumlah ?? 0 }}
    })">
        <i class="bi bi-pencil-square fs-5"></i>
    </button>
    @endif
    <form action="{{ route('pinjaman-beras.destroy', $item->id) }}" method="POST" class="d-inline delete-form">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn btn-link text-danger p-0 m-0">
            <i class="bi bi-trash3-fill fs-5"></i>
        </button>
    </form>
</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Penjualan Beras Tab -->
                    <div class="tab-pane fade" id="penjualan-beras" role="tabpanel">
                        <form id="form-penjualan-beras" class="mb-4">
                            @csrf
                            <div class="input-table">
                                <table class="table mb-0">
                                    <thead>
                                        <tr>
                                            <th style="width: 40%;">Keterangan</th>
                                            <th class="text-center" style="width: 20%;">Jumlah Beras</th>
                                            <th class="text-center" style="width: 25%;">Tanggal</th>
                                            <th class="text-center" style="width: 6%;">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody id="penjualan-beras-input-rows">
                                        <tr class="input-row dynamic-row" data-row="0">
                                            <td><input type="text" class="form-control form-control-sm" name="rows[0][keterangan]" placeholder="Keterangan..." required></td>
                                            <td><input type="text" class="form-control form-control-sm number-format" name="rows[0][jumlah_beras]" placeholder="0" inputmode="decimal" required></td>
                                            <td><input type="date" class="form-control form-control-sm" name="rows[0][tanggal]" value="{{ date('Y-m-d') }}" required></td>
                                            <td class="text-center">
                                                <button type="button" class="remove-row-btn" onclick="removeRow(this)" style="visibility: hidden;">
                                                    <i class="bi bi-x-lg"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                                <div class="submit-section">
                                    <button type="submit" class="btn-submit-all">
                                        <i class="bi bi-check-circle-fill"></i>
                                        Simpan Semua Data
                                    </button>
                                </div>
                            </div>
                        </form>

                        <!-- Data Section -->
                        <div class="table-responsive">
                            <table class="table data-table mb-0">
                                <thead>
                                    <tr>
                                        {{-- <th class="text-center">ID</th> --}}
                                        <th class="text-center">Tanggal</th>
                                        <th>Keterangan</th>
                                        <th class="text-center">Jumlah Beras</th>
                                        <th class="text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($penjualanBeras as $item)
                                    <tr>
                                        <td class="text-center"><span class="text-xs fw-bold">{{ $item->id }}</span></td>
                                        <td class="text-center"><span class="text-xs">{{ $item->tanggal ? $item->tanggal->format('d-m-Y') : '-' }}</span></td>
                                        <td><span class="text-xs">{{ $item->keterangan }}</span></td>
                                        <td class="text-center"><span class="text-xs">{{ number_format($item->jumlah_beras ?? 0, 2, ',', '.') }}</span></td>
                                        <td class="text-center">
    <button type="button" class="btn-edit me-3" onclick="openEditModal('penjualan-beras', {
        id: {{ $item->id }},
        tanggal: '{{ $item->tanggal ? $item->tanggal->format('Y-m-d') : '' }}',
        keterangan: '{{ addslashes($item->keterangan) }}',
        jumlah_beras: {{ $item->jumlah_beras ?? 0 }}
    })">
        <i class="bi bi-pencil-square fs-5"></i>
    </button>
    <form action="{{ route('penjualan-beras.destroy', $item->id) }}" method="POST" class="d-inline delete-form">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn btn-link text-danger p-0 m-0">
            <i class="bi bi-trash3-fill fs-5"></i>
        </button>
    </form>
</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Buku Konga & Menir Tab -->
                    <div class="tab-pane fade" id="buku-konga" role="tabpanel">
                        <form id="form-buku-konga" class="mb-4">
                            @csrf
                            <div class="input-table">
                                <table class="table mb-0">
                                    <thead>
                                        <tr>
                                            <th style="width: 15%;">Petani</th>
                                            <th class="text-center" style="width: 10%;">Karung</th>
                                            <th class="text-center" style="width: 10%;">Konga Giling</th>
                                            <th class="text-center" style="width: 10%;">Konga Jual</th>
                                            {{-- <th class="text-center" style="width: 10%;">Kembalikan</th> --}}
                                            {{-- <th class="text-center" style="width: 10%;">Kembalikan</th> --}}
                                            {{-- <th class="text-center" style="width: 9%;">Menir</th> --}}
                                            <th class="text-center" style="width: 10%;">Menir Jual</th>
                                            <th class="text-center" style="width: 10%;">Tanggal</th>
                                            <th class="text-center" style="width: 6%;">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody id="konga-input-rows">
                                        <tr class="input-row dynamic-row" data-row="0">
                                            <td>
                                                <div style="position: relative;">
                                                    <input type="text" class="form-control form-control-sm petani-search"
                                                           data-target="konga" data-row="0" placeholder="Cari petani..." required>
                                                    <input type="hidden" name="rows[0][petani_id]" class="petani-id">
                                                    <div class="petani-dropdown" style="display:none;"></div>
                                                </div>
                                            </td>
                                            <td><input type="text" class="form-control form-control-sm number-format" name="rows[0][karung_konga]" placeholder="0" inputmode="decimal"></td>
                                            <td><input type="text" class="form-control form-control-sm number-format" name="rows[0][konga_giling]" placeholder="0" inputmode="decimal"></td>
                                            <td><input type="text" class="form-control form-control-sm number-format" name="rows[0][konga_jual]" placeholder="0" inputmode="decimal"></td>
                                            {{-- <td><input type="text" class="form-control form-control-sm number-format" name="rows[0][pinjam_konga]" placeholder="0" inputmode="decimal"></td> --}}
                                            {{-- <td><input type="text" class="form-control form-control-sm number-format" name="rows[0][kembalikan_konga]" placeholder="0" inputmode="decimal"></td> --}}
                                            {{-- <td><input type="text" class="form-control form-control-sm number-format" name="rows[0][menir]" placeholder="0" inputmode="decimal"></td> --}}
                                            <td><input type="text" class="form-control form-control-sm number-format" name="rows[0][menir_jual]" placeholder="0" inputmode="decimal"></td>
                                            <td><input type="date" class="form-control form-control-sm" name="rows[0][tanggal]" value="{{ date('Y-m-d') }}" required></td>
                                            <td class="text-center">
                                                <button type="button" class="remove-row-btn" onclick="removeRow(this)" style="visibility: hidden;">
                                                    <i class="bi bi-x-lg"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                                <div class="submit-section">
                                    <button type="submit" class="btn-submit-all">
                                        <i class="bi bi-check-circle-fill"></i>
                                        Simpan Semua Data
                                    </button>
                                </div>
                            </div>
                        </form>

                        <!-- Data Section -->
                        <div class="table-responsive">
                            <table class="table data-table mb-0">
                                <thead>
                                    <tr>
                                        {{-- <th class="text-center">ID</th> --}}
                                        <th class="text-center">Tanggal</th>
                                        <th>Petani</th>
                                        <th class="text-center">Karung</th>
                                        <th class="text-center">Konga Giling</th>
                                        <th class="text-center">Konga Jual</th>
                                        <th class="text-center">Harga Konga</th>
                                        <th class="text-center">Kembalikan</th>
                                        {{-- <th class="text-center">Kembalikan</th> --}}
                                        {{-- <th class="text-center">Menir</th> --}}
                                        <th class="text-center">Menir Jual</th>
                                        <th class="text-center">Harga Menir</th>
                                        <th class="text-center">Status</th>
                                        <th class="text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($bukuStokKongaMenir as $item)
                                    {{-- <tr data-petani-id="{{ $item->petani_id }}" data-status="{{ $item->status ? '1' : '0' }}"> --}}
                                        <td class="text-center"><span class="text-xs fw-bold">{{ $item->id }}</span></td>
                                        <td class="text-center"><span class="text-xs">{{ $item->tanggal ? $item->tanggal->format('d-m-Y') : '-' }}</span></td>
                                        <td><span class="text-xs">{{ $item->nama_petani }}</span></td>
                                        <td class="text-center"><span class="text-xs">{{ number_format($item->karung_konga ?? 0, 2, ',', '.') }}</span></td>
                                        <td class="text-center"><span class="text-xs">{{ number_format($item->konga_giling ?? 0, 2, ',', '.') }}</span></td>
                                        <td class="text-center"><span class="text-xs">{{ number_format($item->konga_jual ?? 0, 2, ',', '.') }}</span></td>
                                        <td class="text-center"><span class="text-xs">Rp {{ number_format($item->harga_konga ?? 0, 2, ',', '.') }}</span></td>
                                        <td class="text-center"><span class="text-xs">{{ number_format($item->pinjam_konga ?? 0, 2, ',', '.') }}</span></td>
                                        {{-- <td class="text-center"><span class="text-xs">{{ number_format($item->kembalikan_konga ?? 0, 2, ',', '.') }}</span></td> --}}
                                        {{-- <td class="text-center"><span class="text-xs">{{ number_format($item->menir ?? 0, 2, ',', '.') }}</span></td> --}}
                                        <td class="text-center"><span class="text-xs">{{ number_format($item->menir_jual ?? 0, 2, ',', '.') }}</span></td>
                                        <td class="text-center"><span class="text-xs">Rp {{ number_format($item->harga_menir ?? 0, 2, ',', '.') }}</span></td>
                                        <td class="text-center">
                                            <span class="badge bg-gradient-{{ $item->status ? 'success' : 'warning' }}">
                                                {{ $item->status ? 'LUNAS' : 'BELUM LUNAS' }}
                                            </span>
                                        </td>
                                        <td class="text-center">
    <button type="button" class="btn-edit me-3" onclick="openEditModal('buku-konga', {
        id: {{ $item->id }},
        tanggal: '{{ $item->tanggal ? $item->tanggal->format('Y-m-d') : '' }}',
        petani_id: {{ $item->petani_id }},
        nama_petani: '{{ addslashes($item->nama_petani) }}',
        karung_konga: {{ $item->karung_konga ?? 0 }},
        konga_giling: {{ $item->konga_giling ?? 0 }},
        konga_jual: {{ $item->konga_jual ?? 0 }},
        kembalikan_konga: {{ $item->kembalikan_konga ?? 0 }},
        menir: {{ $item->menir ?? 0 }},
        menir_jual: {{ $item->menir_jual ?? 0 }}
    })">
        <i class="bi bi-pencil-square fs-5"></i>
    </button>
    <form action="{{ route('buku-stok-konga-menir.destroy', $item->id) }}" method="POST" class="d-inline delete-form">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn btn-link text-danger p-0 m-0">
            <i class="bi bi-trash3-fill fs-5"></i>
        </button>
    </form>
</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Pinjaman Konga Tab -->
                    <div class="tab-pane fade" id="pinjaman-konga" role="tabpanel">
                        <form id="form-pinjaman-konga" class="mb-4">
                            @csrf
                            <div class="input-table">
                                <table class="table mb-0">
                                    <thead>
                                        <tr>
                                            <th style="width: 35%;">Petani</th>
                                            <th class="text-center" style="width: 25%;">Jumlah Pinjaman</th>
                                            <th class="text-center" style="width: 25%;">Tanggal</th>
                                            <th class="text-center" style="width: 6%;">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody id="pinjaman-konga-input-rows">
                                        <tr class="input-row dynamic-row" data-row="0">
                                            <td>
                                                <div style="position: relative;">
                                                    <input type="text" class="form-control form-control-sm petani-search"
                                                           data-target="pinjaman-konga" data-row="0" placeholder="Cari petani..." required>
                                                    <input type="hidden" name="rows[0][petani_id]" class="petani-id">
                                                    <div class="petani-dropdown" style="display:none;"></div>
                                                </div>
                                            </td>
                                            <td><input type="text" class="form-control form-control-sm number-format" name="rows[0][jumlah]" placeholder="0" inputmode="decimal" required></td>
                                            <td><input type="date" class="form-control form-control-sm" name="rows[0][tanggal]" value="{{ date('Y-m-d') }}" required></td>
                                            <td class="text-center">
                                                <button type="button" class="remove-row-btn" onclick="removeRow(this)" style="visibility: hidden;">
                                                    <i class="bi bi-x-lg"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                                <div class="submit-section">
                                    <button type="submit" class="btn-submit-all">
                                        <i class="bi bi-check-circle-fill"></i>
                                        Simpan Semua Data
                                    </button>
                                </div>
                            </div>
                        </form>

                        <!-- Data Section -->
                        <div class="table-responsive">
                            <table class="table data-table mb-0">
                                <thead>
                                    <tr>
                                        {{-- <th class="text-center">ID</th> --}}
                                        <th class="text-center">Tanggal</th>
                                        <th>Petani</th>
                                        <th class="text-center">Jumlah</th>
                                        <th class="text-center">Status</th>
                                        <th class="text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($pinjamanKonga as $item)
                                    {{-- <tr data-petani-id="{{ $item->petani_id }}" data-status="{{ $item->status ? '1' : '0' }}"> --}}
                                        <td class="text-center"><span class="text-xs fw-bold">{{ $item->id }}</span></td>
                                        <td class="text-center"><span class="text-xs">{{ $item->tanggal ? $item->tanggal->format('d-m-Y') : '-' }}</span></td>
                                        <td><span class="text-xs">{{ $item->nama_petani }}</span></td>
                                        <td class="text-center"><span class="text-xs">{{ number_format($item->jumlah ?? 0, 2, ',', '.') }}</span></td>
                                        <td class="text-center">
                                            <span class="badge bg-gradient-{{ $item->status ? 'success' : 'warning' }}">
                                                {{ $item->status ? 'LUNAS' : 'BELUM LUNAS' }}
                                            </span>
                                        </td>
                                        <td class="text-center">
    @if($item->status == 0)
    <button type="button" class="btn-edit me-3" onclick="openEditModal('pinjaman-konga', {
        id: {{ $item->id }},
        tanggal: '{{ $item->tanggal ? $item->tanggal->format('Y-m-d') : '' }}',
        petani_id: {{ $item->petani_id }},
        nama_petani: '{{ addslashes($item->nama_petani) }}',
        jumlah: {{ $item->jumlah ?? 0 }}
    })">
        <i class="bi bi-pencil-square fs-5"></i>
    </button>
    @endif
    <form action="{{ route('pinjaman-konga.destroy', $item->id) }}" method="POST" class="d-inline delete-form">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn btn-link text-danger p-0 m-0">
            <i class="bi bi-trash3-fill fs-5"></i>
        </button>
    </form>
</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Penjualan Konga & Menir Tab -->
                    <div class="tab-pane fade" id="penjualan-konga" role="tabpanel">
                        <form id="form-penjualan-konga" class="mb-4">
                            @csrf
                            <div class="input-table">
                                <table class="table mb-0">
                                    <thead>
                                        <tr>
                                            <th style="width: 35%;">Keterangan</th>
                                            <th class="text-center" style="width: 17%;">Jumlah Konga</th>
                                            <th class="text-center" style="width: 17%;">Jumlah Menir</th>
                                            <th class="text-center" style="width: 20%;">Tanggal</th>
                                            <th class="text-center" style="width: 11%;">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody id="penjualan-konga-input-rows">
                                        <tr class="input-row dynamic-row" data-row="0">
                                            <td><input type="text" class="form-control form-control-sm" name="rows[0][keterangan]" placeholder="Keterangan..." required></td>
                                            <td><input type="text" class="form-control form-control-sm number-format" name="rows[0][jumlah_konga]" placeholder="0" inputmode="decimal"></td>
                                            <td><input type="text" class="form-control form-control-sm number-format" name="rows[0][jumlah_menir]" placeholder="0" inputmode="decimal"></td>
                                            <td><input type="date" class="form-control form-control-sm" name="rows[0][tanggal]" value="{{ date('Y-m-d') }}" required></td>
                                            <td class="text-center">
                                                <button type="button" class="remove-row-btn" onclick="removeRow(this)" style="visibility: hidden;">
                                                    <i class="bi bi-x-lg"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                                <div class="submit-section">
                                    <button type="submit" class="btn-submit-all">
                                        <i class="bi bi-check-circle-fill"></i>
                                        Simpan Semua Data
                                    </button>
                                </div>
                            </div>
                        </form>

                        <!-- Data Section -->
                        <div class="table-responsive">
                            <table class="table data-table mb-0">
                                <thead>
                                    <tr>
                                        {{-- <th class="text-center">ID</th> --}}
                                        <th class="text-center">Tanggal</th>
                                        <th>Keterangan</th>
                                        <th class="text-center">Jumlah Konga</th>
                                        <th class="text-center">Jumlah Menir</th>
                                        <th class="text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($penjualanKongaMenir as $item)
                                    <tr>
                                        <td class="text-center"><span class="text-xs fw-bold">{{ $item->id }}</span></td>
                                        <td class="text-center"><span class="text-xs">{{ $item->tanggal ? $item->tanggal->format('d-m-Y') : '-' }}</span></td>
                                        <td><span class="text-xs">{{ $item->keterangan }}</span></td>
                                        <td class="text-center"><span class="text-xs">{{ number_format($item->jumlah_konga ?? 0, 2, ',', '.') }}</span></td>
                                        <td class="text-center"><span class="text-xs">{{ number_format($item->jumlah_menir ?? 0, 2, ',', '.') }}</span></td>
                                        <td class="text-center">
    <button type="button" class="btn-edit me-3" onclick="openEditModal('penjualan-konga', {
        id: {{ $item->id }},
        tanggal: '{{ $item->tanggal ? $item->tanggal->format('Y-m-d') : '' }}',
        keterangan: '{{ addslashes($item->keterangan) }}',
        jumlah_konga: {{ $item->jumlah_konga ?? 0 }},
        jumlah_menir: {{ $item->jumlah_menir ?? 0 }},
        harga: {{ $item->harga ?? 0 }}
    })">
        <i class="bi bi-pencil-square fs-5"></i>
    </button>
    <form action="{{ route('penjualan-konga-menir.destroy', $item->id) }}" method="POST" class="d-inline delete-form">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn btn-link text-danger p-0 m-0">
            <i class="bi bi-trash3-fill fs-5"></i>
        </button>
    </form>
</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>










<!-- ============================================
   MODAL HTML - Buku Stok Beras
============================================ -->
<div class="modal-overlay" id="modal-overlay"></div>

<div class="edit-modal" id="edit-modal-buku-beras">
    <div class="edit-modal-header">
        <h5 class="edit-modal-title">
            <i class="bi bi-pencil-square"></i>
            Edit Buku Stok Beras
        </h5>
        <button class="edit-modal-close" onclick="closeEditModal('buku-beras')">&times;</button>
    </div>
    <div class="edit-modal-body">
        <form id="edit-form-buku-beras">
            <input type="hidden" id="edit-buku-beras-id">

            <div class="edit-form-group">
                <label class="edit-form-label">Tanggal</label>
                <input type="date" class="edit-form-control" id="edit-buku-beras-tanggal" required>
            </div>

            <div class="edit-form-group">
                <label class="edit-form-label">Petani</label>
                <div class="edit-petani-search-wrapper">
                    <input type="text" class="edit-form-control" id="edit-buku-beras-petani-search"
                           placeholder="Cari petani..." autocomplete="off">
                    <input type="hidden" id="edit-buku-beras-petani-id">
                    <div class="edit-petani-dropdown" id="edit-buku-beras-petani-dropdown"></div>
                </div>
            </div>

            <div class="edit-form-group">
                <label class="edit-form-label">Jemur</label>
                <input type="text" class="edit-form-control number-format" id="edit-buku-beras-jemur"
                       placeholder="0" inputmode="decimal">
            </div>

            <div class="edit-form-group">
                <label class="edit-form-label">Giling Kotor</label>
                <input type="text" class="edit-form-control number-format" id="edit-buku-beras-giling-kotor"
                       placeholder="0" inputmode="decimal" required>
            </div>

            <div class="edit-form-group">
                <label class="edit-form-label">Beras Pulang</label>
                <input type="text" class="edit-form-control number-format" id="edit-buku-beras-beras-pulang"
                       placeholder="0" inputmode="decimal" required>
            </div>

            <div class="edit-form-group">
                <label class="edit-form-label">Status</label>
                <select class="edit-form-control" id="edit-buku-beras-status">
                    <option value="0">Belum Lunas</option>
                    <option value="1">Lunas</option>
                </select>
            </div>
        </form>
    </div>
    <div class="edit-modal-footer">
        <button class="edit-btn edit-btn-cancel" onclick="closeEditModal('buku-beras')">
            <i class="bi bi-x-circle"></i> Batal
        </button>
        <button class="edit-btn edit-btn-submit" onclick="submitEdit('buku-beras')">
            <i class="bi bi-check-circle"></i> Simpan
        </button>
    </div>
</div>

<!-- ============================================
   MODAL HTML - Pinjaman Beras
============================================ -->
<div class="edit-modal" id="edit-modal-pinjaman-beras">
    <div class="edit-modal-header">
        <h5 class="edit-modal-title">
            <i class="bi bi-pencil-square"></i>
            Edit Pinjaman Beras
        </h5>
        <button class="edit-modal-close" onclick="closeEditModal('pinjaman-beras')">&times;</button>
    </div>
    <div class="edit-modal-body">
        <form id="edit-form-pinjaman-beras">
            <input type="hidden" id="edit-pinjaman-beras-id">

            <div class="edit-form-group">
                <label class="edit-form-label">Tanggal</label>
                <input type="date" class="edit-form-control" id="edit-pinjaman-beras-tanggal" required>
            </div>

            <div class="edit-form-group">
                <label class="edit-form-label">Petani</label>
                <div class="edit-petani-search-wrapper">
                    <input type="text" class="edit-form-control" id="edit-pinjaman-beras-petani-search"
                           placeholder="Cari petani..." autocomplete="off">
                    <input type="hidden" id="edit-pinjaman-beras-petani-id">
                    <div class="edit-petani-dropdown" id="edit-pinjaman-beras-petani-dropdown"></div>
                </div>
            </div>

            <div class="edit-form-group">
                <label class="edit-form-label">Jumlah Pinjaman</label>
                <input type="text" class="edit-form-control number-format" id="edit-pinjaman-beras-jumlah"
                       placeholder="0" inputmode="decimal" required>
            </div>
        </form>
    </div>
    <div class="edit-modal-footer">
        <button class="edit-btn edit-btn-cancel" onclick="closeEditModal('pinjaman-beras')">
            <i class="bi bi-x-circle"></i> Batal
        </button>
        <button class="edit-btn edit-btn-submit" onclick="submitEdit('pinjaman-beras')">
            <i class="bi bi-check-circle"></i> Simpan
        </button>
    </div>
</div>

<!-- ============================================
   MODAL HTML - Pinjaman Konga
============================================ -->
<div class="edit-modal" id="edit-modal-pinjaman-konga">
    <div class="edit-modal-header">
        <h5 class="edit-modal-title">
            <i class="bi bi-pencil-square"></i>
            Edit Pinjaman Konga
        </h5>
        <button class="edit-modal-close" onclick="closeEditModal('pinjaman-konga')">&times;</button>
    </div>
    <div class="edit-modal-body">
        <form id="edit-form-pinjaman-konga">
            <input type="hidden" id="edit-pinjaman-konga-id">

            <div class="edit-form-group">
                <label class="edit-form-label">Tanggal</label>
                <input type="date" class="edit-form-control" id="edit-pinjaman-konga-tanggal" required>
            </div>

            <div class="edit-form-group">
                <label class="edit-form-label">Petani</label>
                <div class="edit-petani-search-wrapper">
                    <input type="text" class="edit-form-control" id="edit-pinjaman-konga-petani-search"
                           placeholder="Cari petani..." autocomplete="off">
                    <input type="hidden" id="edit-pinjaman-konga-petani-id">
                    <div class="edit-petani-dropdown" id="edit-pinjaman-konga-petani-dropdown"></div>
                </div>
            </div>

            <div class="edit-form-group">
                <label class="edit-form-label">Jumlah Pinjaman</label>
                <input type="text" class="edit-form-control number-format" id="edit-pinjaman-konga-jumlah"
                       placeholder="0" inputmode="decimal" required>
            </div>
        </form>
    </div>
    <div class="edit-modal-footer">
        <button class="edit-btn edit-btn-cancel" onclick="closeEditModal('pinjaman-konga')">
            <i class="bi bi-x-circle"></i> Batal
        </button>
        <button class="edit-btn edit-btn-submit" onclick="submitEdit('pinjaman-konga')">
            <i class="bi bi-check-circle"></i> Simpan
        </button>
    </div>
</div>

<!-- ============================================
   MODAL HTML - Buku Konga Menir
============================================ -->
<div class="edit-modal" id="edit-modal-buku-konga">
    <div class="edit-modal-header">
        <h5 class="edit-modal-title">
            <i class="bi bi-pencil-square"></i>
            Edit Buku Konga & Menir
        </h5>
        <button class="edit-modal-close" onclick="closeEditModal('buku-konga')">&times;</button>
    </div>
    <div class="edit-modal-body">
        <form id="edit-form-buku-konga">
            <input type="hidden" id="edit-buku-konga-id">

            <div class="edit-form-group">
                <label class="edit-form-label">Tanggal</label>
                <input type="date" class="edit-form-control" id="edit-buku-konga-tanggal" required>
            </div>

            <div class="edit-form-group">
                <label class="edit-form-label">Petani</label>
                <div class="edit-petani-search-wrapper">
                    <input type="text" class="edit-form-control" id="edit-buku-konga-petani-search"
                           placeholder="Cari petani..." autocomplete="off">
                    <input type="hidden" id="edit-buku-konga-petani-id">
                    <div class="edit-petani-dropdown" id="edit-buku-konga-petani-dropdown"></div>
                </div>
            </div>

            <div class="edit-form-group">
                <label class="edit-form-label">Karung Konga</label>
                <input type="text" class="edit-form-control number-format" id="edit-buku-konga-karung"
                       placeholder="0" inputmode="decimal">
            </div>

            <div class="edit-form-group">
                <label class="edit-form-label">Konga Giling</label>
                <input type="text" class="edit-form-control number-format" id="edit-buku-konga-giling"
                       placeholder="0" inputmode="decimal">
            </div>

            <div class="edit-form-group">
                <label class="edit-form-label">Konga Jual</label>
                <input type="text" class="edit-form-control number-format" id="edit-buku-konga-jual"
                       placeholder="0" inputmode="decimal">
            </div>

            <div class="edit-form-group">
                <label class="edit-form-label">Kembalikan Konga</label>
                <input type="text" class="edit-form-control number-format" id="edit-buku-konga-kembalikan"
                       placeholder="0" inputmode="decimal">
            </div>

            <div class="edit-form-group">
                <label class="edit-form-label">Menir</label>
                <input type="text" class="edit-form-control number-format" id="edit-buku-konga-menir"
                       placeholder="0" inputmode="decimal">
            </div>

            <div class="edit-form-group">
                <label class="edit-form-label">Menir Jual</label>
                <input type="text" class="edit-form-control number-format" id="edit-buku-konga-menir-jual"
                       placeholder="0" inputmode="decimal">
            </div>
        </form>
    </div>
    <div class="edit-modal-footer">
        <button class="edit-btn edit-btn-cancel" onclick="closeEditModal('buku-konga')">
            <i class="bi bi-x-circle"></i> Batal
        </button>
        <button class="edit-btn edit-btn-submit" onclick="submitEdit('buku-konga')">
            <i class="bi bi-check-circle"></i> Simpan
        </button>
    </div>
</div>

<!-- ============================================
   MODAL HTML - Penjualan Beras
============================================ -->
<div class="edit-modal" id="edit-modal-penjualan-beras">
    <div class="edit-modal-header">
        <h5 class="edit-modal-title">
            <i class="bi bi-pencil-square"></i>
            Edit Penjualan Beras
        </h5>
        <button class="edit-modal-close" onclick="closeEditModal('penjualan-beras')">&times;</button>
    </div>
    <div class="edit-modal-body">
        <form id="edit-form-penjualan-beras">
            <input type="hidden" id="edit-penjualan-beras-id">

            <div class="edit-form-group">
                <label class="edit-form-label">Tanggal</label>
                <input type="date" class="edit-form-control" id="edit-penjualan-beras-tanggal" required>
            </div>

            <div class="edit-form-group">
                <label class="edit-form-label">Keterangan</label>
                <input type="text" class="edit-form-control" id="edit-penjualan-beras-keterangan"
                       placeholder="Keterangan..." required>
            </div>

            <div class="edit-form-group">
                <label class="edit-form-label">Jumlah Beras</label>
                <input type="text" class="edit-form-control number-format" id="edit-penjualan-beras-jumlah"
                       placeholder="0" inputmode="decimal" required>
            </div>
        </form>
    </div>
    <div class="edit-modal-footer">
        <button class="edit-btn edit-btn-cancel" onclick="closeEditModal('penjualan-beras')">
            <i class="bi bi-x-circle"></i> Batal
        </button>
        <button class="edit-btn edit-btn-submit" onclick="submitEdit('penjualan-beras')">
            <i class="bi bi-check-circle"></i> Simpan
        </button>
    </div>
</div>

<!-- ============================================
   MODAL HTML - Penjualan Konga Menir
============================================ -->
<div class="edit-modal" id="edit-modal-penjualan-konga">
    <div class="edit-modal-header">
        <h5 class="edit-modal-title">
            <i class="bi bi-pencil-square"></i>
            Edit Penjualan Konga & Menir
        </h5>
        <button class="edit-modal-close" onclick="closeEditModal('penjualan-konga')">&times;</button>
    </div>
    <div class="edit-modal-body">
        <form id="edit-form-penjualan-konga">
            <input type="hidden" id="edit-penjualan-konga-id">

            <div class="edit-form-group">
                <label class="edit-form-label">Tanggal</label>
                <input type="date" class="edit-form-control" id="edit-penjualan-konga-tanggal" required>
            </div>

            <div class="edit-form-group">
                <label class="edit-form-label">Keterangan</label>
                <input type="text" class="edit-form-control" id="edit-penjualan-konga-keterangan"
                       placeholder="Keterangan..." required>
            </div>

            <div class="edit-form-group">
                <label class="edit-form-label">Jumlah Konga</label>
                <input type="text" class="edit-form-control number-format" id="edit-penjualan-konga-jumlah-konga"
                       placeholder="0" inputmode="decimal">
            </div>

            <div class="edit-form-group">
                <label class="edit-form-label">Jumlah Menir</label>
                <input type="text" class="edit-form-control number-format" id="edit-penjualan-konga-jumlah-menir"
                       placeholder="0" inputmode="decimal">
            </div>

            <div class="edit-form-group">
                <label class="edit-form-label">Harga</label>
                <input type="text" class="edit-form-control number-format" id="edit-penjualan-konga-harga"
                       placeholder="0" inputmode="decimal">
            </div>
        </form>
    </div>
    <div class="edit-modal-footer">
        <button class="edit-btn edit-btn-cancel" onclick="closeEditModal('penjualan-konga')">
            <i class="bi bi-x-circle"></i> Batal
        </button>
        <button class="edit-btn edit-btn-submit" onclick="submitEdit('penjualan-konga')">
            <i class="bi bi-check-circle"></i> Simpan
        </button>
    </div>
</div>

























<script>
document.addEventListener('DOMContentLoaded', function() {
    let rowCounters = {
        'beras': 0,
        'pinjaman': 0,
        'konga': 0,
        'pinjaman-konga': 0,
        'penjualan-beras': 0,
        'penjualan-konga': 0
    };

    // ============================================
    // TAB PERSISTENCE - Keep active tab after refresh/submit
    // ============================================
    const activeTab = sessionStorage.getItem('activeTab');
    if (activeTab) {
        const tabElement = document.querySelector(`button[data-bs-target="${activeTab}"]`);
        if (tabElement) {
            const tab = new bootstrap.Tab(tabElement);
            tab.show();
        }
    }

    document.querySelectorAll('button[data-bs-toggle="tab"]').forEach(button => {
        button.addEventListener('shown.bs.tab', function(e) {
            sessionStorage.setItem('activeTab', e.target.getAttribute('data-bs-target'));
        });
    });

    // ============================================
// SEARCH PETANI GLOBAL
// ============================================
const searchGlobalInput = document.getElementById('search-petani-global');
const searchResults = document.getElementById('search-petani-results');

if (searchGlobalInput) {
    searchGlobalInput.addEventListener('input', function() {
        const searchTerm = this.value.trim();

        if (searchTerm.length > 0) {
            fetch(`/search-petani?term=${encodeURIComponent(searchTerm)}`)
                .then(response => response.json())
                .then(data => {
                    searchResults.innerHTML = '';
                    searchResults.style.display = 'block';

                    if (data.length === 0) {
                        searchResults.innerHTML = '<div class="p-3 text-center text-muted">Tidak ada petani ditemukan</div>';
                        return;
                    }

                    data.forEach(petani => {
    const div = document.createElement('div');
    div.className = 'search-petani-item';
    div.innerHTML = `
        <div class="petani-avatar">${petani.nama.charAt(0).toUpperCase()}</div>
        <div class="petani-info">
            <div class="petani-name">
                ${petani.nama}
                <span class="pinjaman-badge">
                    Beras: ${smartFormatNumber(petani.pinjaman_beras)} Kg | Konga: ${smartFormatNumber(petani.pinjaman_konga)} Karung
                </span>
            </div>
            <div class="petani-alamat">${petani.alamat || '-'}</div>
        </div>
    `;

                        div.addEventListener('click', function() {
                            searchGlobalInput.value = petani.nama;
                            searchGlobalInput.setAttribute('data-selected-id', petani.id);
                            searchResults.style.display = 'none';

                            // Apply all filters
                            applyAllFilters();
                        });

                        searchResults.appendChild(div);
                    });
                });
        } else {
            searchResults.style.display = 'none';
            // Reset selected petani
            searchGlobalInput.removeAttribute('data-selected-id');
            // Apply filters (reset petani filter)
            applyAllFilters();
        }
    });

    // Close dropdown when clicking outside
    document.addEventListener('click', function(e) {
        if (!e.target.closest('.search-petani-wrapper')) {
            searchResults.style.display = 'none';
        }
    });
}

// ============================================
// CLEAR ALL FORMS ON PAGE LOAD
// ============================================
document.querySelectorAll('form[id^="form-"]').forEach(form => {
    clearForm(form);
});

// ============================================
// SMART NUMBER FORMATTING
// ============================================
function smartFormatNumber(value) {
    const num = parseFloat(value) || 0;

    if (num % 1 === 0) {
        return num.toLocaleString('id-ID', {
            minimumFractionDigits: 0,
            maximumFractionDigits: 0
        });
    }

    const formatted = num.toLocaleString('id-ID', {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2
    });

    return formatted.replace(/,(\d)0$/, ',$1');
}

document.querySelectorAll('.data-table .text-xs').forEach(cell => {
    const text = cell.textContent.trim();

    if (text.startsWith('Rp')) {
        const numberPart = text.replace(/[^\d,.]/g, '').replace(/\./g, '').replace(',', '.');
        cell.textContent = 'Rp ' + smartFormatNumber(numberPart);
        return;
    }

    if (text.includes('-') || text.length < 3 || isNaN(text.replace(/[,.]/g, ''))) {
        return;
    }

    const numberValue = text.replace(/\./g, '').replace(',', '.');
    if (!isNaN(numberValue)) {
        cell.textContent = smartFormatNumber(numberValue);
    }
});

document.querySelectorAll('.stok-value').forEach(elem => {
    const text = elem.childNodes[0]?.textContent?.trim();
    if (text) {
        const numberValue = text.replace(/\./g, '').replace(',', '.');
        if (!isNaN(numberValue)) {
            elem.childNodes[0].textContent = smartFormatNumber(numberValue) + ' ';
        }
    }
});



    // ============================================
    // NUMBER FORMATTING
    // ============================================
    document.addEventListener('input', function(e) {
        if (e.target.classList.contains('number-format')) {
            let cursorPosition = e.target.selectionStart;
            let originalValue = e.target.value.replace(/,/g, '');
            originalValue = originalValue.slice(0, 15);

            if (!/^\d*\.?\d*$/.test(originalValue)) {
                e.target.value = e.target.value.slice(0, cursorPosition - 1) + e.target.value.slice(cursorPosition);
                return;
            }

            let parts = originalValue.split('.');
            parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ',');
            let formattedValue = parts.join('.');
            e.target.value = formattedValue;

            let newCursorPosition = cursorPosition + (formattedValue.length - originalValue.length);
            e.target.setSelectionRange(newCursorPosition, newCursorPosition);
        }
    });

    // ============================================
    // PETANI AUTOCOMPLETE (in forms)
    // ============================================
    document.addEventListener('input', function(e) {
        if (e.target.classList.contains('petani-search')) {
            const searchTerm = e.target.value.trim();
            const dropdown = e.target.parentElement.querySelector('.petani-dropdown');
            const hiddenInput = e.target.parentElement.querySelector('.petani-id');

            if (searchTerm.length > 0) {
                fetch(`/search-petani?term=${encodeURIComponent(searchTerm)}`)
                    .then(response => response.json())
                    .then(data => {
                        dropdown.innerHTML = '';
                        dropdown.style.display = 'block';

                        if (data.length === 0) {
                            dropdown.innerHTML = '<div class="p-3 text-center text-muted" style="font-size: 0.8rem;">Tidak ditemukan</div>';
                            return;
                        }

                        data.forEach(petani => {
    const div = document.createElement('div');
    div.className = 'search-petani-item';
    div.innerHTML = `
        <div class="petani-avatar">${petani.nama.charAt(0).toUpperCase()}</div>
        <div class="petani-info">
            <div class="petani-name">
                ${petani.nama}
                <span class="pinjaman-badge">
                    Beras: ${smartFormatNumber(petani.pinjaman_beras)} Kg | Konga: ${smartFormatNumber(petani.pinjaman_konga)} Karung
                </span>
            </div>
            <div class="petani-alamat">${petani.alamat || '-'}</div>
        </div>
    `;

                            div.addEventListener('click', function() {
                                e.target.value = petani.nama;
                                hiddenInput.value = petani.id;
                                dropdown.style.display = 'none';
                            });

                            dropdown.appendChild(div);
                        });
                    });
            } else {
                dropdown.style.display = 'none';
            }
        }
    });

    // Close dropdown when clicking outside
    document.addEventListener('click', function(e) {
        if (!e.target.classList.contains('petani-search')) {
            document.querySelectorAll('.petani-dropdown').forEach(dropdown => {
                dropdown.style.display = 'none';
            });
        }
    });

    // ============================================
    // ADD ROW ON ENTER KEY
    // ============================================
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Enter' && e.target.closest('.dynamic-row')) {
            e.preventDefault();
            const row = e.target.closest('.dynamic-row');
            const tbody = row.parentElement;
            const formId = tbody.closest('form').id;

            let target = '';
            if (formId === 'form-buku-beras') target = 'beras';
            else if (formId === 'form-pinjaman-beras') target = 'pinjaman';
            else if (formId === 'form-buku-konga') target = 'konga';
            else if (formId === 'form-pinjaman-konga') target = 'pinjaman-konga';
            else if (formId === 'form-penjualan-beras') target = 'penjualan-beras';
            else if (formId === 'form-penjualan-konga') target = 'penjualan-konga';

            addNewRow(tbody, target);
        }
    });

    // ============================================
    // ADD NEW ROW FUNCTION
    // ============================================
    function addNewRow(tbody, target) {
        rowCounters[target]++;
        const newRowIndex = rowCounters[target];
        const firstRow = tbody.querySelector('.dynamic-row');
        const newRow = firstRow.cloneNode(true);

        newRow.setAttribute('data-row', newRowIndex);

        // Reset all inputs
        newRow.querySelectorAll('input, select').forEach(input => {
            if (input.type === 'date') {
                input.value = new Date().toISOString().split('T')[0];
            } else if (input.classList.contains('petani-id')) {
                input.value = '';
            } else if (input.classList.contains('petani-search')) {
                input.value = '';
            } else {
                input.value = input.type === 'text' ? '' : '0';
            }

            // Update name attributes
            const name = input.getAttribute('name');
            if (name) {
                input.setAttribute('name', name.replace(/\[\d+\]/, `[${newRowIndex}]`));
            }
        });

        // Show remove button
        const removeBtn = newRow.querySelector('.remove-row-btn');
        if (removeBtn) {
            removeBtn.style.visibility = 'visible';
        }

        tbody.appendChild(newRow);

        // Focus first input in new row
        const firstInput = newRow.querySelector('input:not([type="hidden"])');
        if (firstInput) {
            firstInput.focus();
        }
    }

    // ============================================
    // REMOVE ROW FUNCTION
    // ============================================
    window.removeRow = function(button) {
        const row = button.closest('.dynamic-row');
        const tbody = row.parentElement;

        if (tbody.querySelectorAll('.dynamic-row').length > 1) {
            row.remove();
        }
    };

    // ============================================
    // CLEAR FORM FUNCTION
    // ============================================
    function clearForm(form) {
        form.querySelectorAll('.dynamic-row').forEach((row, index) => {
            if (index === 0) {
                // Reset first row
                row.querySelectorAll('input, select').forEach(input => {
                    if (input.type === 'date') {
                        input.value = new Date().toISOString().split('T')[0];
                    } else if (input.type === 'hidden') {
                        input.value = '';
                    } else if (input.tagName === 'SELECT') {
                        input.selectedIndex = 0;
                    } else {
                        input.value = '';
                    }
                });
            } else {
                // Remove extra rows
                row.remove();
            }
        });
    }

    // ============================================
    // FORM SUBMISSIONS
    // ============================================
    function setupFormSubmission(formId, routeName) {
        const form = document.getElementById(formId);
        if (!form) return;

        form.addEventListener('submit', function(e) {
            e.preventDefault();

            const csrfToken = this.querySelector('[name="_token"]').value;
            const rows = [];

            this.querySelectorAll('.dynamic-row').forEach(row => {
                const rowData = {};
                row.querySelectorAll('input, select').forEach(input => {
                    const name = input.getAttribute('name');
                    if (!name) return;
                    const match = name.match(/\[([^\]]+)\]$/);
                    if (!match) return;
                    const field = match[1];
                    let val = input.value;

                    if (input.classList.contains('number-format')) {
                        val = val.replace(/,/g, '') || '0';
                    }

                    rowData[field] = val;
                });
                rows.push(rowData);
            });

            fetch(routeName, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ rows })
            })
            .then(r => r.json())
            .then(data => {
                if (data.success) {
                    // Clear form before reload
                    clearForm(form);
                    location.reload();
                } else {
                    console.error(data);
                    alert('Error: ' + (data.message ?? 'Terjadi kesalahan'));
                }
            })
            .catch(err => {
                alert('Error: ' + err.message);
            });
        });
    }

    // Setup all forms
    setupFormSubmission('form-buku-beras', '{{ route("buku-stok-beras.store") }}');
    setupFormSubmission('form-pinjaman-beras', '{{ route("pinjaman-beras.store") }}');
    setupFormSubmission('form-buku-konga', '{{ route("buku-stok-konga-menir.store") }}');
    setupFormSubmission('form-pinjaman-konga', '{{ route("pinjaman-konga.store") }}');
    setupFormSubmission('form-penjualan-beras', '{{ route("penjualan-beras.store") }}');
    setupFormSubmission('form-penjualan-konga', '{{ route("penjualan-konga-menir.store") }}');

    // ============================================
    // DELETE CONFIRMATION
    // ============================================
    document.querySelectorAll('.delete-form').forEach(form => {
        form.addEventListener('submit', function(e) {
            if (!confirm('Apakah Anda yakin ingin menghapus data ini?')) {
                e.preventDefault();
            }
        });
    });

    // ============================================
    // CLEAR ALL FORMS ON PAGE LOAD
    // ============================================
    document.querySelectorAll('form[id^="form-"]').forEach(form => {
        clearForm(form);
    });
});

// ============================================
// FILTER STATUS GLOBAL (Kombinasi dengan Search Petani)
// ============================================
document.getElementById('filter-status-global').addEventListener('change', function() {
    applyAllFilters();
});

// Reset filter saat ganti tab
document.querySelectorAll('#bukuStokTabs .nav-link').forEach(tab => {
    tab.addEventListener('shown.bs.tab', function() {
        document.getElementById('filter-status-global').value = '';
        applyAllFilters();
    });
});

// Fungsi untuk apply semua filter (petani + status)
function applyAllFilters() {
    const statusFilter = document.getElementById('filter-status-global').value;
    const searchInput = document.getElementById('search-petani-global');
    const selectedPetaniId = searchInput ? searchInput.getAttribute('data-selected-id') : null;

    document.querySelectorAll('.data-table tbody tr').forEach(row => {
        let showByPetani = true;
        let showByStatus = true;

        // Filter by Petani
        if (selectedPetaniId) {
            const rowPetaniId = row.getAttribute('data-petani-id');
            showByPetani = (rowPetaniId === selectedPetaniId);
        }

        // Filter by Status (berdasarkan data-status attribute)
        if (statusFilter !== '') {
            const rowStatus = row.getAttribute('data-status');
            showByStatus = (rowStatus === statusFilter);
        }

        // Tampilkan row jika kedua filter terpenuhi
        row.style.display = (showByPetani && showByStatus) ? '' : 'none';
    });
}

// ============================================
// SIMPLE SCROLL ISOLATION FOR DROPDOWNS
// ============================================
(function() {
    // Intercept all wheel events on petani-dropdown
    document.addEventListener('wheel', function(e) {
        const dropdown = e.target.closest('.petani-dropdown');

        if (dropdown) {
            const scrollTop = dropdown.scrollTop;
            const scrollHeight = dropdown.scrollHeight;
            const clientHeight = dropdown.clientHeight;
            const delta = e.deltaY;

            // Scrolling up but already at top
            if (delta < 0 && scrollTop <= 0) {
                e.preventDefault();
                return;
            }

            // Scrolling down but already at bottom
            if (delta > 0 && scrollTop + clientHeight >= scrollHeight) {
                e.preventDefault();
                return;
            }

            // Otherwise, prevent propagation to stop page scroll
            e.stopPropagation();
        }
    }, { passive: false, capture: true });
})();

// ============================================
// SMART NUMBER FORMATTING - Add after DOMContentLoaded
// ============================================
function smartFormatNumber(value) {
    // Convert to number and handle null/undefined
    const num = parseFloat(value) || 0;

    // Check if it's a whole number
    if (num % 1 === 0) {
        // Format tanpa desimal
        return num.toLocaleString('id-ID', {
            minimumFractionDigits: 0,
            maximumFractionDigits: 0
        });
    }

    // Has decimal, format and remove trailing zeros
    const formatted = num.toLocaleString('id-ID', {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2
    });

    // Remove trailing zero after decimal (e.g., "10,50" becomes "10,5")
    return formatted.replace(/,(\d)0$/, ',$1');
}

// Apply to all number displays on page load
document.querySelectorAll('.data-table .text-xs').forEach(cell => {
    const text = cell.textContent.trim();

    // Skip if it starts with "Rp" (price fields)
    if (text.startsWith('Rp')) {
        const numberPart = text.replace(/[^\d,.]/g, '').replace(/\./g, '').replace(',', '.');
        const formatted = smartFormatNumber(numberPart);
        cell.textContent = 'Rp ' + formatted;
        return;
    }

    // Skip if it's a date or ID or text
    if (text.includes('-') || text.length < 3 || isNaN(text.replace(/[,.]/g, ''))) {
        return;
    }

    // Format regular numbers
    const numberValue = text.replace(/\./g, '').replace(',', '.');
    if (!isNaN(numberValue)) {
        cell.textContent = smartFormatNumber(numberValue);
    }
});

// Also apply to stok cards
document.querySelectorAll('.stok-value').forEach(elem => {
    const text = elem.childNodes[0]?.textContent?.trim();
    if (text) {
        const numberValue = text.replace(/\./g, '').replace(',', '.');
        if (!isNaN(numberValue)) {
            const formatted = smartFormatNumber(numberValue);
            elem.childNodes[0].textContent = formatted + ' ';
        }
    }
});

























// ============================================
// GLOBAL VARIABLES
// ============================================
let currentEditType = '';
let currentEditData = {};

// ============================================
// OPEN EDIT MODAL
// ============================================
function openEditModal(type, data) {
    currentEditType = type;
    currentEditData = data;

    // Show overlay
    document.getElementById('modal-overlay').classList.add('active');
    document.body.style.overflow = 'hidden';



    // Show modal
    const modal = document.getElementById(`edit-modal-${type}`);
    modal.classList.add('active');

    // Populate fields based on type
    populateModalFields(type, data);

    // Prevent body scroll
    document.body.style.overflow = 'hidden';
}

// ============================================
// CLOSE EDIT MODAL
// ============================================
function closeEditModal(type) {
    document.getElementById('modal-overlay').classList.remove('active');
    document.getElementById(`edit-modal-${type}`).classList.remove('active');

    document.body.style.overflow = '';

    // Clear form
    document.getElementById(`edit-form-${type}`).reset();
}

// Close modal when clicking overlay
document.getElementById('modal-overlay').addEventListener('click', function() {
    if (currentEditType) {
        closeEditModal(currentEditType);
    }
});

// ============================================
// POPULATE MODAL FIELDS
// ============================================
function populateModalFields(type, data) {
    switch(type) {
        case 'buku-beras':
            document.getElementById('edit-buku-beras-id').value = data.id;
            document.getElementById('edit-buku-beras-tanggal').value = data.tanggal;
            document.getElementById('edit-buku-beras-petani-search').value = data.nama_petani;
            document.getElementById('edit-buku-beras-petani-id').value = data.petani_id;
            document.getElementById('edit-buku-beras-jemur').value = formatNumber(data.jemur);
            document.getElementById('edit-buku-beras-giling-kotor').value = formatNumber(data.giling_kotor);
            document.getElementById('edit-buku-beras-beras-pulang').value = formatNumber(data.beras_pulang);
            document.getElementById('edit-buku-beras-status').value = data.status;
            break;

        case 'pinjaman-beras':
            document.getElementById('edit-pinjaman-beras-id').value = data.id;
            document.getElementById('edit-pinjaman-beras-tanggal').value = data.tanggal;
            document.getElementById('edit-pinjaman-beras-petani-search').value = data.nama_petani;
            document.getElementById('edit-pinjaman-beras-petani-id').value = data.petani_id;
            document.getElementById('edit-pinjaman-beras-jumlah').value = formatNumber(data.jumlah);
            break;

        case 'pinjaman-konga':
            document.getElementById('edit-pinjaman-konga-id').value = data.id;
            document.getElementById('edit-pinjaman-konga-tanggal').value = data.tanggal;
            document.getElementById('edit-pinjaman-konga-petani-search').value = data.nama_petani;
            document.getElementById('edit-pinjaman-konga-petani-id').value = data.petani_id;
            document.getElementById('edit-pinjaman-konga-jumlah').value = formatNumber(data.jumlah);
            break;

        case 'buku-konga':
            document.getElementById('edit-buku-konga-id').value = data.id;
            document.getElementById('edit-buku-konga-tanggal').value = data.tanggal;
            document.getElementById('edit-buku-konga-petani-search').value = data.nama_petani;
            document.getElementById('edit-buku-konga-petani-id').value = data.petani_id;
            document.getElementById('edit-buku-konga-karung').value = formatNumber(data.karung_konga);
            document.getElementById('edit-buku-konga-giling').value = formatNumber(data.konga_giling);
            document.getElementById('edit-buku-konga-jual').value = formatNumber(data.konga_jual);
            document.getElementById('edit-buku-konga-kembalikan').value = formatNumber(data.kembalikan_konga);
            document.getElementById('edit-buku-konga-menir').value = formatNumber(data.menir);
            document.getElementById('edit-buku-konga-menir-jual').value = formatNumber(data.menir_jual);
            break;

        case 'penjualan-beras':
            document.getElementById('edit-penjualan-beras-id').value = data.id;
            document.getElementById('edit-penjualan-beras-tanggal').value = data.tanggal;
            document.getElementById('edit-penjualan-beras-keterangan').value = data.keterangan;
            document.getElementById('edit-penjualan-beras-jumlah').value = formatNumber(data.jumlah_beras);
            break;

        case 'penjualan-konga':
            document.getElementById('edit-penjualan-konga-id').value = data.id;
            document.getElementById('edit-penjualan-konga-tanggal').value = data.tanggal;
            document.getElementById('edit-penjualan-konga-keterangan').value = data.keterangan;
            document.getElementById('edit-penjualan-konga-jumlah-konga').value = formatNumber(data.jumlah_konga);
            document.getElementById('edit-penjualan-konga-jumlah-menir').value = formatNumber(data.jumlah_menir);
            document.getElementById('edit-penjualan-konga-harga').value = formatNumber(data.harga);
            break;
    }

    // Apply number formatting to all number-format inputs in the modal
    const modal = document.getElementById(`edit-modal-${type}`);
    modal.querySelectorAll('.number-format').forEach(input => {
        setupNumberFormatting(input);
    });
}

// ============================================
// SUBMIT EDIT
// ============================================
function submitEdit(type) {
    const form = document.getElementById(`edit-form-${type}`);
    if (!form.checkValidity()) {
        form.reportValidity();
        return;
    }

    const id = document.getElementById(`edit-${type}-id`).value;
    const routeMap = {
        'buku-beras': 'buku-stok-beras',
        'pinjaman-beras': 'pinjaman-beras',
        'pinjaman-konga': 'pinjaman-konga',
        'buku-konga': 'buku-stok-konga-menir',
        'penjualan-beras': 'penjualan-beras',
        'penjualan-konga': 'penjualan-konga-menir'
    };

    const deleteRoute = `/${routeMap[type]}/${id}`;
    const storeRoute = `/${routeMap[type]}`;

    // Prepare data
    const data = prepareEditData(type);

    if (!data) {
        alert('Data tidak valid');
        return;
    }

    // Show loading
    const submitBtn = event.target;
    const originalText = submitBtn.innerHTML;
    submitBtn.innerHTML = '<i class="bi bi-hourglass-split"></i> Menyimpan...';
    submitBtn.disabled = true;

    // Step 1: Delete old data
    fetch(deleteRoute, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('[name="_token"]').value,
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json'
        },
        body: JSON.stringify({
            _method: 'DELETE'
        })
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Gagal menghapus data lama');
        }

        // Step 2: Create new data
        return fetch(storeRoute, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('[name="_token"]').value,
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            },
            body: JSON.stringify({ rows: [data] })
        });
    })
    .then(response => response.json())
    .then(result => {
        if (result.success) {
            closeEditModal(type);
            location.reload();
        } else {
            throw new Error(result.message || 'Terjadi kesalahan');
        }
    })
    .catch(error => {
        alert('Error: ' + error.message);
        submitBtn.innerHTML = originalText;
        submitBtn.disabled = false;
    });
}

// ============================================
// PREPARE EDIT DATA
// ============================================
function prepareEditData(type) {
    switch(type) {
        case 'buku-beras':
            return {
                petani_id: document.getElementById('edit-buku-beras-petani-id').value,
                tanggal: document.getElementById('edit-buku-beras-tanggal').value,
                jemur: parseFormattedNumber(document.getElementById('edit-buku-beras-jemur').value),
                giling_kotor: parseFormattedNumber(document.getElementById('edit-buku-beras-giling-kotor').value),
                beras_pulang: parseFormattedNumber(document.getElementById('edit-buku-beras-beras-pulang').value),
                status: document.getElementById('edit-buku-beras-status').value
            };

        case 'pinjaman-beras':
            return {
                petani_id: document.getElementById('edit-pinjaman-beras-petani-id').value,
                tanggal: document.getElementById('edit-pinjaman-beras-tanggal').value,
                jumlah: parseFormattedNumber(document.getElementById('edit-pinjaman-beras-jumlah').value)
            };

        case 'pinjaman-konga':
            return {
                petani_id: document.getElementById('edit-pinjaman-konga-petani-id').value,
                tanggal: document.getElementById('edit-pinjaman-konga-tanggal').value,
                jumlah: parseFormattedNumber(document.getElementById('edit-pinjaman-konga-jumlah').value)
            };

        case 'buku-konga':
            return {
                petani_id: document.getElementById('edit-buku-konga-petani-id').value,
                tanggal: document.getElementById('edit-buku-konga-tanggal').value,
                karung_konga: parseFormattedNumber(document.getElementById('edit-buku-konga-karung').value),
                konga_giling: parseFormattedNumber(document.getElementById('edit-buku-konga-giling').value),
                konga_jual: parseFormattedNumber(document.getElementById('edit-buku-konga-jual').value),
                kembalikan_konga: parseFormattedNumber(document.getElementById('edit-buku-konga-kembalikan').value),
                menir: parseFormattedNumber(document.getElementById('edit-buku-konga-menir').value),
                menir_jual: parseFormattedNumber(document.getElementById('edit-buku-konga-menir-jual').value)
            };

        case 'penjualan-beras':
            return {
                tanggal: document.getElementById('edit-penjualan-beras-tanggal').value,
                keterangan: document.getElementById('edit-penjualan-beras-keterangan').value,
                jumlah_beras: parseFormattedNumber(document.getElementById('edit-penjualan-beras-jumlah').value)
            };

        case 'penjualan-konga':
            return {
                tanggal: document.getElementById('edit-penjualan-konga-tanggal').value,
                keterangan: document.getElementById('edit-penjualan-konga-keterangan').value,
                jumlah_konga: parseFormattedNumber(document.getElementById('edit-penjualan-konga-jumlah-konga').value),
                jumlah_menir: parseFormattedNumber(document.getElementById('edit-penjualan-konga-jumlah-menir').value),
                harga: parseFormattedNumber(document.getElementById('edit-penjualan-konga-harga').value)
            };

        default:
            return null;
    }
}

// ============================================
// PETANI SEARCH IN MODAL
// ============================================
document.addEventListener('DOMContentLoaded', function() {
    // Setup petani search for all edit modals
    const searchConfigs = [
        { search: 'edit-buku-beras-petani-search', dropdown: 'edit-buku-beras-petani-dropdown', hidden: 'edit-buku-beras-petani-id' },
        { search: 'edit-pinjaman-beras-petani-search', dropdown: 'edit-pinjaman-beras-petani-dropdown', hidden: 'edit-pinjaman-beras-petani-id' },
        { search: 'edit-pinjaman-konga-petani-search', dropdown: 'edit-pinjaman-konga-petani-dropdown', hidden: 'edit-pinjaman-konga-petani-id' },
        { search: 'edit-buku-konga-petani-search', dropdown: 'edit-buku-konga-petani-dropdown', hidden: 'edit-buku-konga-petani-id' }
    ];

    searchConfigs.forEach(config => {
        const searchInput = document.getElementById(config.search);
        const dropdown = document.getElementById(config.dropdown);
        const hiddenInput = document.getElementById(config.hidden);

        if (!searchInput || !dropdown || !hiddenInput) return;

        let searchTimeout;

        searchInput.addEventListener('input', function() {
            clearTimeout(searchTimeout);
            const term = this.value.trim();

            if (term.length < 2) {
                dropdown.classList.remove('show');
                return;
            }

            searchTimeout = setTimeout(() => {
                fetch(`/buku-stok/search-petani?term=${encodeURIComponent(term)}`)
                    .then(r => r.json())
                    .then(petanis => {
                        if (petanis.length === 0) {
                            dropdown.innerHTML = '<div style="padding: 1rem; text-align: center; color: #8392ab;">Tidak ada hasil</div>';
                            dropdown.classList.add('show');
                            return;
                        }

                        dropdown.innerHTML = petanis.map(p => `
                            <div class="edit-petani-dropdown-item" data-id="${p.id}" data-name="${p.nama}">
                                <div class="petani-name">${p.nama}</div>
                                <div class="petani-info">${p.alamat || ''}</div>
                            </div>
                        `).join('');

                        dropdown.classList.add('show');

                        // Add click handlers
                        dropdown.querySelectorAll('.edit-petani-dropdown-item').forEach(item => {
                            item.addEventListener('click', function() {
                                searchInput.value = this.dataset.name;
                                hiddenInput.value = this.dataset.id;
                                dropdown.classList.remove('show');
                            });
                        });
                    })
                    .catch(err => console.error('Search error:', err));
            }, 300);
        });

        // Close dropdown when clicking outside
        document.addEventListener('click', function(e) {
            if (!searchInput.contains(e.target) && !dropdown.contains(e.target)) {
                dropdown.classList.remove('show');
            }
        });
    });
});

// ============================================
// HELPER FUNCTIONS
// ============================================
function formatNumber(value) {
    if (!value && value !== 0) return '';
    return parseFloat(value).toLocaleString('id-ID', {
        minimumFractionDigits: 0,
        maximumFractionDigits: 2
    });
}

function parseFormattedNumber(value) {
    if (!value) return 0;
    return parseFloat(value.replace(/\./g, '').replace(',', '.')) || 0;
}

function setupNumberFormatting(input) {
    input.addEventListener('input', function() {
        let val = this.value.replace(/[^0-9,]/g, '');

        // Split by comma
        let parts = val.split(',');

        // Format integer part
        if (parts[0]) {
            parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, '.');
        }

        // Rejoin
        this.value = parts.join(',');
    });
}



</script>

@endsection
