<template>
    <div class="p-4 d-flex flex-column h-100">
        
        <div class="mt-3">
            <div class="d-flex justify-content-center align-items-center mb-4">
                <h2 class="me-3 ff-secondary mb-0">258</h2>
                <div>
                    <p class="text-muted mb-0">Total Projects</p>
                    <p class="text-success fw-medium mb-0"><span
                            class="badge bg-success-subtle rounded-pill bg-success-subtle text-success p-1"><i
                                class="ri-arrow-right-up-line"></i></span> +3 New </p>
                </div>
            </div>
            <div class="d-flex justify-content-between border-bottom border-bottom-dashed py-2">
                <p class="fw-medium mb-0 fs-12">
                    <i class="ri-checkbox-blank-circle-fill text-success align-middle me-2"></i> Successful Login
                </p>
                <div>
                    <span class="text-success fw-semibold fs-12">{{statistics[0]}}</span>
                </div>
            </div>
            <div class="d-flex justify-content-between border-bottom border-bottom-dashed py-2">
                <p class="fw-medium mb-0 fs-12">
                    <i class="ri-checkbox-blank-circle-fill text-warning align-middle me-2"></i> Suspicious Login
                </p>
                <div>
                    <span class="text-warning fw-semibold fs-12">{{statistics[1]}}</span>
                </div>
            </div>
            <div class="d-flex justify-content-between py-2">
                <p class="fw-medium mb-0 fs-12">
                    <i class="ri-checkbox-blank-circle-fill text-danger align-middle me-2"></i> Login Attempts
                </p>
                <div>
                    <span class="text-danger fw-semibold fs-12">{{statistics[2]}}</span>
                </div>
            </div>
        </div>
        <hr class="text-muted"/>
        <div class="table-responsive mt-2">
            <table class="table table-borderless table-sm table-centered align-middle table-nowrap mb-0">
                <tbody class="border-0">
                    <tr>
                        <td>
                            <h4 class="text-truncate fs-14 fs-medium mb-0"><i
                                    class="ri-stop-fill align-middle fs-18 text-primary me-2"></i>Desktop Users </h4>
                        </td>
                        <td>
                            <p class="text-muted mb-0"><svg xmlns="http://www.w3.org/2000/svg" width="16px"
                                    height="16px" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round"
                                    class="feather feather-users me-2 icon-sm me-2 icon-sm">
                                    <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                                    <circle cx="9" cy="7" r="4"></circle>
                                    <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                                    <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                                </svg>78.56k </p>
                        </td>
                        <td class="text-end">
                            <p class="text-success fw-medium fs-12 mb-0"><i
                                    class="ri-arrow-up-s-fill fs-5 align-middle"></i>2.08% </p>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <h4 class="text-truncate fs-14 fs-medium mb-0"><i
                                    class="ri-stop-fill align-middle fs-18 text-warning me-2"></i>Mobile Users </h4>
                        </td>
                        <td>
                            <p class="text-muted mb-0"><svg xmlns="http://www.w3.org/2000/svg" width="16px"
                                    height="16px" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round"
                                    class="feather feather-users me-2 icon-sm me-2 icon-sm">
                                    <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                                    <circle cx="9" cy="7" r="4"></circle>
                                    <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                                    <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                                </svg>105.02k </p>
                        </td>
                        <td class="text-end">
                            <p class="text-danger fw-medium fs-12 mb-0"><i
                                    class="ri-arrow-down-s-fill fs-5 align-middle"></i>10.52% </p>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <h4 class="text-truncate fs-14 fs-medium mb-0"><i
                                    class="ri-stop-fill align-middle fs-18 text-info me-2"></i>Tablet Users </h4>
                        </td>
                        <td>
                            <p class="text-muted mb-0"><svg xmlns="http://www.w3.org/2000/svg" width="16px"
                                    height="16px" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round"
                                    class="feather feather-users me-2 icon-sm me-2 icon-sm">
                                    <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                                    <circle cx="9" cy="7" r="4"></circle>
                                    <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                                    <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                                </svg>42.89k </p>
                        </td>
                        <td class="text-end">
                            <p class="text-danger fw-medium fs-12 mb-0"><i
                                    class="ri-arrow-down-s-fill fs-5 align-middle"></i>7.36% </p>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>
<script>
export default {
    props: ['statistics']
}
</script>
