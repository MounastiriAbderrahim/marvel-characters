import {Component, OnInit} from '@angular/core';
import {HttpClient} from "@angular/common/http";
import {CharacterService} from 'src/app/service/character.service';
import {Characters} from "src/app/model/Characters";

@Component({
    selector: 'app-root',
    templateUrl: './app.component.html',
    styleUrls: ['./app.component.css']
})
export class AppComponent implements OnInit {
    loading = true;
    config: any;
    collection = { count: 0, data: [] };

    constructor(public http: HttpClient,
                public crudApi: CharacterService,) {
        this.config = {
            itemsPerPage: 60,
            currentPage: 1,
            totalItems: this.collection.count
        };
    }

    ngOnInit() {
        this.getData();
    }

    getData() {
        this.crudApi.getAll().subscribe(
            response => {
                this.crudApi.list = response["hydra:member"];
                this.collection.data = response["hydra:member"];
                this.collection.count = response["hydra:totalItems"];
                this.loading = false;
            }
        );
    }

    pageChanged(event: any){
        this.config.currentPage = event;
    }
}
