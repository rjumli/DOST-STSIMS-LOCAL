<template>
    <div>
        <div class="app-menu navbar-menu">
            <div class="navbar-brand-box">
                <Link href="/" class="logo logo-dark">
                    <span class="logo-sm"><img src="/imagess/logo-sm.png" alt="" height="30" /></span>
                    <span class="logo-lg"><img src="/imagess/logo-dark.png" alt="" height="30" /></span>
                </Link>
                <Link href="/" class="logo logo-light">
                    <span class="logo-sm"> <img src="/imagess/logo-sm.png" alt="" height="30" /> </span>
                    <span class="logo-lg"><img src="/imagess/logo-light.png" alt="" height="30" /> </span>
                </Link>
                <button type="button" class="btn btn-sm p-0 fs-20 header-item float-end btn-vertical-sm-hover" id="vertical-hover" @click="initActiveMenu">
                    <i class="ri-record-circle-line"></i>
                </button>
            </div>

            <!-- <simplebar id="scrollbar" class="h-100" ref="scrollbar"> -->
            <ul class="navbar-nav" id="navbar-nav">
                <li class="nav-item">
                    <Link class="nav-link menu-link" :class="{'active': $page.component.startsWith('Modules/Dashboard') }" href="/home">
                        <i class='ri ri-apps-line '></i>
                        <span data-key="t-dashboards">Home</span>
                    </Link>
                </li>
                <li class="nav-item">
                    <Link class="nav-link menu-link" :class="{'active': $page.component.startsWith('Modules/Monitoring') }" href="/monitoring">
                        <i class='bx bxs-webcam'></i>
                        <span data-key="t-students">Monitoring</span>
                    </Link>
                </li>  
                <li class="nav-item">
                    <Link class="nav-link menu-link" :class="{'active': $page.component.startsWith('Modules/Schools') }" href="/schools">
                        <i class='ri-hotel-fill'></i>
                        <span data-key="t-dashboards">Schools</span>
                    </Link>
                </li>
                <li class="nav-item">
                    <b-link class="nav-link menu-link" :class="{'active': $page.component.startsWith('Modules/Scholars') }" data-bs-toggle="collapse" role="button"
                        aria-expanded="false" aria-controls="sidebar1">
                        <i class="ri-team-fill"></i>
                        <span data-key="t-dashboards">Scholars</span>
                    </b-link>
                    <div class="collapse menu-dropdown" id="sidebar1">
                        <ul class="nav nav-sm flex-column">
                            <li class="nav-item">
                                <Link class="nav-link" :class="{'active': $page.component.startsWith('Modules/Scholars/Lists') }" href="/scholars/listing">
                                    <span data-key="t-dashboards">Lists</span>
                                </Link>
                            </li>
                            <li class="nav-item">
                                <Link class="nav-link" :class="{'active': $page.component.startsWith('Modules/Scholars/Qualifiers') }" href="/scholars/qualifiers">
                                    <span data-key="t-dashboards">Qualifiers</span>
                                </Link>
                            </li>
                            <li class="nav-item">
                                <Link class="nav-link" :class="{'active': $page.component.startsWith('Modules/Scholars/Endorsements') }" href="/scholars/endorsements">
                                    <span data-key="t-dashboards">Endorsements</span>
                                </Link>
                            </li>
                            <li class="nav-item">
                                <Link class="nav-link" :class="{'active': $page.component.startsWith('Modules/Scholars/Transferees') }" href="/scholars/transferees">
                                    <span data-key="t-dashboards">Transferees</span>
                                </Link>
                            </li>
                        </ul>
                    </div>
                </li>
                <li class="nav-item">
                    <Link class="nav-link menu-link" :class="{'active': $page.component.startsWith('Modules/Enrollments') }" href="/enrollments">
                        <i class='ri-pages-line' ></i>
                        <span data-key="t-students">Enrollments</span>
                    </Link>
                </li>   
                <li class="nav-item">
                    <Link class="nav-link menu-link" :class="{'active': $page.component.startsWith('Modules/FinancialBenefits') }" href="/financial-benefits">
                        <i class='ri-wallet-3-fill ' ></i>
                        <span data-key="t-students">Financial Benefits</span>
                    </Link>
                </li>   
                <li class="nav-item">
                    <b-link class="nav-link menu-link" :class="{'active': $page.component.startsWith('Modules/Staffs') }" data-bs-toggle="collapse" role="button"
                        aria-expanded="false" aria-controls="sidebar1">
                        <i class="bx bx-user-circle"></i>
                        <span data-key="t-dashboards">Staffs</span>
                    </b-link>
                    <div class="collapse menu-dropdown" id="sidebar1">
                        <ul class="nav nav-sm flex-column">
                            <li class="nav-item">
                                <Link class="nav-link" :class="{'active': $page.component.startsWith('Modules/Staffs/Lists') }" href="/staffs/lists">
                                    <span data-key="t-dashboards">Lists</span>
                                </Link>
                            </li>
                            <li class="nav-item">
                                <Link class="nav-link" :class="{'active': $page.component.startsWith('Modules/Staffs/Roles') }" href="/staffs/roles">
                                    <span data-key="t-dashboards">Roles</span>
                                </Link>
                            </li>
                        </ul>
                    </div>
                </li>
                <li class="nav-item">
                    <Link class="nav-link menu-link" :class="{'active': $page.component.startsWith('Modules/Insights') }" href="/insights">
                        <i class='ri-line-chart-fill' ></i>
                        <span data-key="t-students">Insights</span>
                    </Link>
                </li>
                <li class="nav-item">
                    <b-link class="nav-link menu-link" :class="{'active': $page.component.startsWith('Modules/Logs') }" data-bs-toggle="collapse" role="button"
                        aria-expanded="false" aria-controls="sidebar1">
                        <i class="ri-list-check-2"></i>
                        <span data-key="t-dashboards">Logs</span>
                    </b-link>
                    <div class="collapse menu-dropdown" id="sidebar1">
                        <ul class="nav nav-sm flex-column">
                            <li class="nav-item">
                                <Link class="nav-link" :class="{'active': $page.component.startsWith('Modules/Logs/Authentications') }" href="/logs/authentication">
                                    <span data-key="t-dashboards">Authentication Logs</span>
                                </Link>
                            </li>
                            <li class="nav-item">
                                <Link class="nav-link" :class="{'active': $page.component.startsWith('Modules/Logs/Activities') }" href="/logs/activities">
                                    <span data-key="t-dashboards">Activity Logs</span>
                                </Link>
                            </li>
                        </ul>
                    </div>
                </li>
                <li class="nav-item">
                    <b-link class="nav-link menu-link" :class="{'active': $page.component.startsWith('Modules/Settings') }" data-bs-toggle="collapse" role="button"
                        aria-expanded="false" aria-controls="sidebar1">
                        <i class="ri-settings-4-fill"></i>
                        <span data-key="t-dashboards">Settings</span>
                    </b-link>
                    <div class="collapse menu-dropdown" id="sidebar1">
                        <ul class="nav nav-sm flex-column">
                            <li class="nav-item">
                                <Link class="nav-link" :class="{'active': $page.component.startsWith('Modules/Settings/Backup') }" href="/settings/backups">
                                    <span data-key="t-dashboards">Backup and Restore</span>
                                </Link>
                            </li>
                        </ul>
                    </div>
                </li>
            </ul>
            <!-- </simplebar> -->
            <div class="sidebar-background"></div>
        </div>
    </div>
    <div class="vertical-overlay" id="overlay"></div>
</template>
<script>
export default {
    mounted() {
        window.addEventListener('keydown', this.handleKeyPress);
    },
    destroyed() {
        window.removeEventListener('keydown', this.handleKeyPress);
    },
    methods: {
        handleKeyPress(event) {
            if (event.altKey) {
                switch (event.key) {
                    case '1':
                    this.$inertia.visit('/home');
                    break;
                    case '2':
                    this.$inertia.visit('/monitoring');
                    break;
                    case '3':
                    this.$inertia.visit('/schools');
                    break;
                    case '4':
                    this.$inertia.visit('/qualifiers');
                    break;
                    case '5':
                    this.$inertia.visit('/scholars');
                    break;
                    case '6':
                    this.$inertia.visit('/enrollments');
                    break;
                    case '7':
                    this.$inertia.visit('/financial-benefits');
                    break;
                    case '8':
                    this.$inertia.visit('/accounting');
                    break;
                    case '9':
                    this.$inertia.visit('/insights');
                    break;
                    case '10':
                    this.$inertia.visit('/staffs');
                    break;
                    case '11':
                    this.$inertia.visit('/setting');
                    break;
                }
            }
        },
    },
}
</script>