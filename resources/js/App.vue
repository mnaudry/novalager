<template>
    <div class="container">
        <nav class="nav">
            <ul class="menu">
                <li><router-link to="/">Acceuil</router-link></li>
                <li><router-link to="/create">Produit</router-link></li>
            </ul>
        </nav>
            <router-view/>
    </div>
</template>


<style lang="scss">
.nav {  
    background-color: #e9e9e9;
    .menu {
        list-style:none;
        display:flex;
        flex-direction:row;
        padding:0;
        margin:0;
        font-size:1.5rem;
        font-weight:700;
        li {
            a {
                text-decoration:none;
                display:block;
                padding:0.5rem;
                color:#111;

                &:hover {
                     background-color: #ddd;
                     color: black;
                }

                &.router-link-active {
                    background-color: #2196F3;
                    color: white;
                }

            }
        }
    }
}

</style>