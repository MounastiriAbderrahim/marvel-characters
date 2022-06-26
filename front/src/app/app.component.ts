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

    constructor(public http: HttpClient,
                public crudApi: CharacterService,) {
    }

    ngOnInit() {
        this.getData();
    }

    getData() {
        this.crudApi.getAll().subscribe(
            response => {
                this.crudApi.list = response["hydra:member"];
                this.loading = false;
            }
        );
    }
}
