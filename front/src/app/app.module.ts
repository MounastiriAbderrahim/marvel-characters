import { NgModule } from '@angular/core';
import { BrowserModule } from '@angular/platform-browser';
import {HttpClientModule} from '@angular/common/http';
import { AppComponent } from './app.component';
import { CharactersComponent } from './characters/characters.component';
import {MapToIterable} from './PropertiesPipe';

@NgModule({
  declarations: [
    AppComponent,
    CharactersComponent,
    MapToIterable
],
  imports: [
    BrowserModule,
    HttpClientModule
  ],
  providers: [],
  bootstrap: [AppComponent]
})
export class AppModule { }
