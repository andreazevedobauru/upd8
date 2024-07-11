<template>
    <div class="container mt-5">
        <h1 class="mb-4">Cidades</h1>

        <div class="d-flex justify-content-end mb-4">
            <router-link :to="{ name: 'CreateCity' }" class="btn btn-success" style="margin-right: 1em;"><i class="bi bi-plus-lg"></i> Cadastrar Cidade</router-link>
            <span class="btn btn-primary" @click="openSearch = !openSearch"><i class="bi bi-search"></i> Pesquisar</span>
        </div>
        <div v-show="openSearch" class="card fadeIn " style="margin-bottom: 1em; transition: opacity 0.5s ease;">
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-3">
                        <label for="name" class="form-label">Nome:</label>
                        <input v-model="filterCity.name" type="text" class="form-control" id="name" name="name" >
                    </div>

                </div>
                <button @click="fetchItems()" class="btn btn-primary"><i class="bi bi-search"></i> Filtrar</button>
            </div>
        </div>
{{ items.value }}
        <div class="table-responsive">
            <table class="table table-hover table-bordered">
                <thead class="table-dark">
                    <tr>
                        <th scope="col">ID</th>
                        <th scope="col">Cidade</th>
                        <th scope="col">Estado</th>
                        <th scope="col">País</th>
                        <th scope="col" style="width: 20%;"></th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(item, i) in items.data" :key="i">
                        <td>{{ item.city_id }}</td>
                        <td>{{ item.name }}</td>
                        <td>{{ item.state.name }}</td>
                        <td>{{ item.state.country.name }}</td>
                        <td>
                            <router-link :to="{ name: 'EditCity', params: { id: item.city_id } }"
                                class="btn btn-sm btn-outline-primary me-2"><i class="bi bi-pencil-square me-1"></i>
                                Editar</router-link>

                            <button class="btn btn-sm btn-outline-danger" @click="openDeleteConfirmation(item.city_id)"> <i
                                    class="bi bi-trash me-1"></i> Deletar</button>
                        </td>
                    </tr>
                </tbody>
            </table>
            <Bootstrap5Pagination
                :data="items"
                @pagination-change-page="fetchItems"
                align="center"
                size="small"
            />
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="deleteConfirmationModal" tabindex="-1" aria-labelledby="deleteConfirmationModalLabel"
        aria-hidden="true" ref="deleteModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteConfirmationModalLabel">Realmente deseja deletar essa cidade?</h5>
                    <button type="button" class="btn-close" @click="closeModal" aria-label="Fechar"></button>
                </div>
                <div class="modal-body">
                    Voce realmente deseja deletar?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" @click="closeModal">Cancelar</button>
                    <button type="button" class="btn btn-danger" @click="confirmDelete">Deletar</button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { onMounted, ref, reactive } from 'vue';
import apiRoutes from '../../api'
import { Bootstrap5Pagination } from 'laravel-vue-pagination';
import { toast } from 'vue3-toastify';

export default {
    components:{
        Bootstrap5Pagination
    },
    setup() {
        const items = ref([]);
        const itemIdToDelete = ref(null);
        const deleteModal = ref(null);
        const successMessage = ref('');
        const openSearch = ref(false);
        const filterCity =  reactive({ name: '', per_page: 100, page: 1 });

        const fetchItems = async (page = 1) => {
            try {
                filterCity.page = page;
                const params = new URLSearchParams();
                Object.keys(filterCity).forEach(key => {
                    if (filterCity[key]) {
                        params.append(key, filterCity[key]);
                    }
                });

                const response = await axios.get(`${apiRoutes.cities.get}?${params.toString()}`);

                items.value = response.data;
            } catch (error) {
                console.error('Error fetching items:', error);
            }
        };

        const openDeleteConfirmation = (id) => {
            itemIdToDelete.value = id;
            const modalInstance = new bootstrap.Modal(deleteModal.value);
            modalInstance.show();
        };

        const closeModal = () => {
            const modalInstance = bootstrap.Modal.getInstance(deleteModal.value);
            if (modalInstance) {
                modalInstance.hide();
            }
        };

        const confirmDelete = async () => {
            if (itemIdToDelete.value !== null) {
                await deleteItem(itemIdToDelete.value);
                toast('Deletado com sucesso', {
                    autoClose: 4500,
                    position: toast.POSITION.BOTTOM_RIGHT,
                });
                closeModal();
            }
        };

        const deleteItem = async (id) => {
            try {
                await axios.delete(`${apiRoutes.cities.delete}${id}`);
                await fetchItems(filterCity.page)

            } catch (error) {
                console.error('Error deleting item:', error);
            }
        };

        onMounted(fetchItems);

        return {
            items,
            openDeleteConfirmation,
            confirmDelete,
            deleteItem,
            deleteModal,
            closeModal,
            successMessage,
            openSearch,
            filterCity,
            fetchItems
        };
    }
}
</script>

<style>
.fadeIn {
    -webkit-animation-name: fadeIn;
    animation-name: fadeIn;
    -webkit-animation-duration: 1s;
    animation-duration: 1s;
    -webkit-animation-fill-mode: both;
    animation-fill-mode: both;
}
@-webkit-keyframes fadeIn {
    0% {opacity: 0;}
    100% {opacity: 1;}
}
@keyframes fadeIn {
    0% {opacity: 0;}
    100% {opacity: 1;}
}
</style>
