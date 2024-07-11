<template>
    <div>
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
            <div class="container">
                <router-link class="navbar-brand text-white" to="/">Upd8</router-link>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                    aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav">
                        <li class="nav-item">
                            <router-link class="nav-link text-white" :to="{ name: 'ListClient' }">Clientes</router-link>
                        </li>
                        <li class="nav-item">
                            <router-link class="nav-link text-white" to="/sellers">Representantes</router-link>
                        </li>
                        <li class="nav-item">
                            <router-link class="nav-link text-white" to="/cities">Cidades</router-link>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>

        <!-- Main Content -->
        <div class="container my-4">
            <router-view></router-view>
        </div>
    </div>
</template>

<script>
export default {
}
</script>
