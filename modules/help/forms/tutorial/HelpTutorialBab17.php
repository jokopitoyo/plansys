<?php

class HelpTutorialBab17 extends Form {
	 public function getForm() {
        return array (
            'title' => 'Plansys - Tutorial',
            'layout' => array (
                'name' => 'full-width',
                'data' => array (
                    'col1' => array (
                        'type' => 'mainform',
                    ),
                ),
            ),
        );
    }

    public function getFields() {
        return array (
            array (
                'renderInEditor' => 'Yes',
                'type' => 'Text',
                'value' => '								
				<center>
				<h2>Tutorial Plansys</h2><hr>
				</center>				
				<div class=\"daftar_isi\">					
					<p><a ng-url="/help/tutorial/bab1">INTRODUCTION</a> 					
					<p><a ng-url="/help/tutorial/bab2">TABEL DATABASE</a> 	
					<p><a ng-url="/help/tutorial/bab3">MODULE MANAGEMENT</a> 
					<p><a ng-url="/help/tutorial/bab4">MODEL MANAGEMENT</a> 
					<p><a ng-url="/help/tutorial/bab5">CRUD GENERATOR</a> 	
					<p><a ng-url="/help/tutorial/bab6">VIEW MANAGEMENT</a> 
					<p><a ng-url="/help/tutorial/bab7">CONTROLLER MANAGEMENT</a> 
					<p><a ng-url="/help/tutorial/bab8">ROLE MANAGEMENT</a> 	
					<p><a ng-url="/help/tutorial/bab9">USER MANAGEMENT</a> 
					<p><a ng-url="/help/tutorial/bab10">MENU TREE EDITOR</a> 
					<p><a ng-url="/help/tutorial/bab11">ACCESS PERMISSIONS</a> 	
					<p><a ng-url="/help/tutorial/bab12">REPORT MANAGEMENT</a> 
					<p><a ng-url="/help/tutorial/bab13">EMAIL BUILDER</a> 
					<p><a ng-url="/help/tutorial/bab14">SERVICE MANAGEMENT</a> 	
					<p><a ng-url="/help/tutorial/bab15">DATABASE MANAGEMENT</a> 
					<p><a ng-url="/help/tutorial/bab16">SETTING MANAGEMENT</a> 	
					<p><a ng-url="/help/tutorial/bab17">REPOSITORY</a>	
					<hr>
					<p align="right"><a href="https://drive.google.com/folderview?id=0B1jwrrSeSILiNDh3eWc5RkYtcHM&usp=sharing" target="_blank"> <strong><i> >>> Download PDF</strong> </i></a>													
				</div>
				<div class=\"judul_bab\">					
					<h3 id="bab17">Bab XVII. REPOSITORY</h3>
				</div>
				<div class=\"sub_daftar_isi\">		
					<table>
						<td style="width:20%">
							<a href="#17.1">17.1. Konsep Repository</a><br>							
						</td>					
					</table>
				</div>				
				<div class=\"isi\">
					<hr>					
					<h4 id="17.1">17.1. Konsep Repository</h4><a href="#bab17"><i> back to top >></i></a>
					<p>Repository merupakan direktori untuk penyimpanan semua data yang telah diupload user. User –user tersebut mengakses module yang sama dalam sistem aplikasi. Atau dengan arti lain, repository merupakan direktori penyimpanan yang diberikan untuk masing-masing module dalam sistem aplikasi ini. 
				<div>				
				',
            ),
        );
    }
}