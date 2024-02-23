{% extends 'baseback.html.twig' %}

{% block title %} Projects {% endblock %}

{% block content %}

  <!-- Page Wrapper -->
  <div id="wrapper">


    <!-- Content Wrapper -->
    <div id="content-wrapper" class="d-flex flex-column">

        <!-- Main Content -->
        <div id="content">

            
            <div class="container-fluid">

                <!-- Page Heading -->
                <h1 class="h3 mb-2 text-gray-800">Tables</h1>
                <p class="mb-4">DataTables is a third party plugin that is used to generate the demo table below.
                    For more information about DataTables, please visit the <a target="_blank"
                        href="https://datatables.net">official DataTables documentation</a>.</p>

                <!-- DataTales Example -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">DataTables Example</h6>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Description</th>
                                        <th>Category</th>
                                        <th>Wallet Address</th>
                                        <th>Date of Creation</th>
                                        <th>Photo URL</th>
                                    </tr>
                                </thead>
                                <tfoot>
                                    <tr>
                                        <th>Name</th>
                                        <th>Description</th>
                                        <th>Category</th>
                                        <th>Wallet Address</th>
                                        <th>Date of Creation</th>
                                        <th>Photo URL</th>
                                    </tr>
                                </tfoot>
                                <tbody>
                                    {% for project in projects %}
                                    <tr>
                                        <td>{{ project.getNom() }}</td>
                                        <td>{{ project.getDescription() }}</td>
                                        <td>{{ project.getCategory() }}</td>
                                        <td>{{ project.getWalletAddress() }}</td>
                                        <td>{{ project.getDateDeCreation()|date('Y-m-d') }}</td>
                                        <td>{{ project.getPhotoURL() }}</td>
                                    </tr>
                                    {% endfor %}
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

            </div>
            <!-- /.container-fluid -->

        </div>


{% endblock %}