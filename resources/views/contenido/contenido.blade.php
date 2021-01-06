    @extends('principal')
    @section('contenido')
    @if(Auth::check())
            <!--  ADMIN   -->
            @if (Auth::user()->idrol == 1) 
                <template v-if="menu==0">
                    <!-- <example-component></example-component> -->
                </template>
                <template v-if="menu==1">
                    <categoria></categoria>
                </template>
                <template v-if="menu==2">
                    <medida></medida>
                </template>
                <template v-if="menu==3">
                    <producto></producto>
                </template>
                <template v-if="menu==4">
                    <espacio></espacio>
                </template>
                <template v-if="menu==5">
                    <movimiento></movimiento>
                </template>
                <template v-if="menu==6">
                    <rol></rol>
                </template>
                <template v-if="menu==7">
                    <usuario></usuario>
                </template>
                <template v-if="menu==10">
                    <persona></persona>
                </template>
                <template v-if="menu==11">
                    <compra></compra>
                </template>
                <!--  SECRE   -->
            @elseif (Auth::user()->idrol == 2)
            <template v-if="menu==1">
                    <categoria></categoria>
                </template>
                <template v-if="menu==2">
                    <medida></medida>
                </template>
                <template v-if="menu==3">
                    <producto></producto>
                </template>
                <template v-if="menu==4">
                    <espacio></espacio>
                </template>
                <template v-if="menu==5">
                    <movimiento></movimiento>
                </template>
                <!--  VENDEDOR   -->
            @elseif (Auth::user()->idrol == 3)
                
            @else

            @endif

        @endif
        
    @endsection