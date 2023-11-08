@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header"> 
                    <form>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Start Date</label>
                                    <input type="date" class="form-control" id="start_date" >
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">End Date</label>
                                    <input type="date" class="form-control" id="end_date" max="<?= date('Y-m-d')?>">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label class="form-label">Tipe</label>
                                    <select class="form-control" id="type">
                                        <option value="">All</option>
                                        <option value="Pemasukkan">Pemasukkan</option>
                                        <option value="Pengeluaran">Pengeluaran</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        
                        <div  class="btn btn-primary" id="btn_search">Search</div>
                    </form>    
                </div>
                <div class="card-body" id="grafik_perbandingan_pemasukkan_dan_karyawan">
                    
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('js')
<script>
    $('#btn_search').on('click', function(){
        let start_date = $('#start_date').val()
        let end_date = $('#end_date').val()
        let type = $('#type').val()

        if(start_date == ''){
            Swal.fire("Start Date Can't Be Empty");
        }else if(end_date == ''){
            Swal.fire("End Date Can't Be Empty");
        }else{
            $.ajax({
                url : "{{url('/data/get_grafic')}}",
                type : "POST",
                data:{'start_date':start_date,'end_date':end_date,'type':type},
                headers: {
                'X-CSRF-TOKEN': "{{csrf_token()}}",
                },
                success: function(data) {
                    let dt = JSON.parse(data)
                    get_diagramtotal(dt)
                    get_diagrampemasukkan(dt)
                    get_diagrampengeluaran(dt)
                }
            });
        }
    })

    function get_diagramtotal(data){
        var groupedData = {};

        $.each(data, function(index, item) {
            var key = item.Tanggal + item.Tipe;

            if (groupedData[key]) {
                groupedData[key].Nominal += parseFloat(item.Nominal);
            } else {
                groupedData[key] = {
                    Tanggal: item.Tanggal,
                    Tipe: item.Tipe,
                    Nominal: parseFloat(item.Nominal)
                };
            }
        });
        let arr_pengeluaran = [];
        let arr_pemasukkan = [];
        let arr_tanggal = [];

        // Membuat array baru yang hanya berisi data yang dibutuhkan saja
        for (let property in groupedData) {
            if(groupedData[property].Tipe == 'Pemasukkan') {
                arr_pemasukkan.push(parseInt(groupedData[property].Nominal))
                arr_tanggal.push(groupedData[property].Tanggal)
            }else{
                arr_pengeluaran.push(parseInt(groupedData[property].Nominal))
            }
        }

        Highcharts.chart('grafik_perbandingan_pemasukkan_dan_karyawan', {
            chart: {
                type: 'column'
            },
            title: {
                text: 'Perbandingan Pengeluaran dan Pemasukkan'
            },
            xAxis: {
                categories: arr_tanggal
            },
            yAxis: {
                title: {
                    text: 'Jumlah'
                }
            },
            series: [{
                name: 'Pemasukkan',
                data: arr_pemasukkan
            }, {
                name: 'Pengeluaran',
                data: arr_pengeluaran
            }]
            });
    }

    function get_diagrampemasukkan(datajson){

    }

    function get_diagrampengeluaran(datajson){

    }
</script>
@endsection