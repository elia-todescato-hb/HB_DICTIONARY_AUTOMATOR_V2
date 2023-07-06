<template>
  <Rotta>
    <Titolo titolo={t.titolo_gestione_strutture[$l]}/>
    {#if in_caricamento}
      <Caricatore/>
    {:else}
      <div class="bottone-nuovo-abbonamento">
        <Bottone
            variante="primario"
            on:click={evento => vai_a_nuovo_abbonamento()}>
          {t.nuova_struttura[$l]}
        </Bottone>
      </div>

      <ContenitoreBase >
        <TitoloSecondario>{t.lista_strutture[$l]}</TitoloSecondario>

        <div class="padding-positivi">
          <Tabella
              {colonne}
              {valore_cella}
              righe={$sessioni_selezionabili}
              dizionario={t}
              let:riga={riga}
              let:colonna={colonna}
              let:valore={valore}>
            {#if colonna == "abbonamento"}
              <div class="in-linea">
                {#if ricava_abbonamento(riga) == null}
                  <Fanalino stato="ignorato"/>
                {:else}
                  <Fanalino stato={ricava_abbonamento(riga)}/>
                  &nbsp; {t.stati[ricava_abbonamento(riga)][$l]}
                {/if}
              </div>
            {:else if colonna == ""}
              {#if $dominio == "veneto" && riga.abbonamenti != null && riga.abbonamenti[riga.abbonamenti.length - 1].flusso_personalizzato != "veneto"}
                <Bottone
                    piccolo={true}
                    variante="primario"
                    on:click={evento => crea_abbonamento_da_struttura(riga)}>
                  <div class="in-linea">
                    {t.acquisisci_licenza[$l]}
                    &nbsp; <Icona tipo="freccia-destra"/>
                  </div>
                </Bottone>
              {:else}
                <Bottone
                    piccolo={true}
                    variante="primario-invertito"
                    on:click={evento => vai_a_gestione_struttura(riga)}>
                  <div class="in-linea">
                    {t.gestisci[$l]}
                    &nbsp; <Icona tipo="freccia-destra"/>
                  </div>
                </Bottone>
              {/if}
            {:else}
              {valore_cella(riga, colonna, valore)}
            {/if}
          </Tabella>
        </div>
        
        {#if è_nuovo_abbonato}
          <div class="messaggio-abbonamento">
            <p>{t.messaggio_nuovo_abbonato[$l]}</p>
    
            <Bottone
                variante="primario"
                on:click={vai_a_nuovo_abbonamento}>
              {t.nuova_struttura[$l]}
            </Bottone>
          </div>
        {/if}
      </ContenitoreBase>
    {/if}
  </Rotta>
</template>

<style>
  div.in-linea {
    display:      flex;
    align-items:  center;
  }

  :global(div.bottone-nuovo-abbonamento){
    position: fixed;
    top:      0;
    right:    0;
    padding: 11px 14px;
  }
  :global(div.messaggio-abbonamento){
    height: calc(100% - 183px);
    margin: 0 auto
  }
</style>

<script>
  import Rotta from "@/base/componenti/Rotta.svelte"
  import Titolo from "@/base/componenti/Titolo.svelte"
  import TitoloSecondario from "@/base/componenti/TitoloSecondario.svelte"
  import ContenitoreBase from "@/base/componenti/ContenitoreBase.svelte"
  import Caricatore from "@/base/componenti/Caricatore.svelte"
  import Tabella from "@/base/componenti/Tabella.svelte"
  import Fanalino from "@/base/componenti/Fanalino.svelte"
  import Bottone from "@/base/componenti/Bottone.svelte"
  import Icona from "@/base/componenti/Icona.svelte"
  import { sessioni_selezionabili } from "@/portale/sorgenti/sessione"
  import { gruppi_comparabili } from "@/portale/sorgenti/sessione"
  import { sessione_corrente } from "@/portale/sorgenti/sessione"
  import { utente_corrente } from "@/base/sorgenti/svuby"
  import { localizzazione as l } from "@/base/sorgenti/svuby"
  import { avvia_localizzazione } from "@/base/sorgenti/svuby"
  import { dominio } from "@/portale/sorgenti/dominio"
  import { abbonamento } from "@/portale/sorgenti/abbonamento"
  import { parametri_abbonamento_personalizzato } from "@/portale/sorgenti/abbonamento"
  import { onMount } from "svelte";

  const t = avvia_localizzazione(dizionario_gestione_strutture)
  const colonne = [ "nome", "territorio", "zona", "località", "abbonamento", "" ]

  let in_caricamento = true
  let abbonamenti = []

  $: è_gestore_struttura = $utente_corrente != null && $utente_corrente.ruolo == "gestore_struttura" && $utente_corrente.id_strutture.length == 0
  $: è_ospite = $utente_corrente != null && $utente_corrente.ruolo == "ospite"
  $: è_nuovo_abbonato = è_gestore_struttura || è_ospite

  $: if (è_nuovo_abbonato) navigatore.verso("/nuovo_abbonamento/riprendi")

  ////
  // Richiede gli abbonamenti.
  async function richiedi_abbonamenti() {

    in_caricamento = true

    let risposta = await retro.chiama(
      "GET",
      retro.estremi.licenze.abbonamenti)
    
    abbonamenti = risposta.contenuto.abbonamenti

    in_caricamento = false
  }

  ////
  // Determina il valore di una cella.
  function valore_cella(riga, colonna, valore, indice_riga, indice_colonna) {
    switch (colonna) {
      case "territorio":
        let indice_territorio = Object.keys($gruppi_comparabili).find(indice_gruppo => $gruppi_comparabili[indice_gruppo]._id == riga.id_territorio)
        let territorio = $gruppi_comparabili[indice_territorio]
        let nome_territorio = null

        if (territorio != null) nome_territorio = territorio.nome

        return nome_territorio || "-"
      case "zona":
        let indice_zona = Object.keys($gruppi_comparabili).find(indice_gruppo => $gruppi_comparabili[indice_gruppo]._id == riga.id_zona)
        let zona = $gruppi_comparabili[indice_zona]
        let nome_zona = null

        if (zona != null) nome_zona = zona.nome

        return nome_zona || "-"
      case "località":
        let indice_località = Object.keys($gruppi_comparabili).find(indice_gruppo => $gruppi_comparabili[indice_gruppo]._id == riga.id_località)
        let località = $gruppi_comparabili[indice_località]
        let nome_località = null

        if (località != null) nome_località = località.nome
        
        return nome_località || "-"
      default:
        return valore || "-"
    }
  }

  ////
  // Ricava lo stato dell'abbonamento di una struttura
  function ricava_abbonamento(struttura) {
    let abbonamento = abbonamenti.find(abbonamento => abbonamento.id_struttura == struttura._id)

    if (abbonamento != null) return abbonamento.stato
  }

  ////
  // Cambia sessione e va alla gestione della struttura data
  async function crea_abbonamento_da_struttura(struttura) {
    if (struttura.abbonameti != null && struttura.abbonamenti[0]) return

    let risposta = await retro.chiama("GET",
      retro.estremi.licenze.struttura(struttura._id))

    let abbonamento_predefinito = parametri_abbonamento_personalizzato($l, $dominio)
    abbonamento_predefinito.passo_corrente = 2
    abbonamento_predefinito.passo_raggiunto = 2
    abbonamento_predefinito.id_struttura_preesistente = risposta.contenuto.struttura._id
    abbonamento_predefinito.struttura = risposta.contenuto.struttura
    abbonamento.sovrascrivi(abbonamento_predefinito)
    navigatore.verso("/nuovo_abbonamento/piano")
  }

  ////
  // Cambia sessione e va alla gestione della struttura data
  function vai_a_gestione_struttura(struttura) {
    if (struttura._id == $sessione_corrente._id) return navigatore.verso("/gestione_strutture/struttura")

    sessione_corrente.cambia_in(struttura)

    navigatore.verso("/gestione_strutture/struttura")
  }

  ////
  // Va alla creazione di un nuovo abbonamento
  function vai_a_nuovo_abbonamento() {
    navigatore.verso("/nuovo_abbonamento/riprendi")
  }

  onMount(richiedi_abbonamenti)
</script>

<script context="module">
  export const dizionario_gestione_strutture = {
    titolo_gestione_strutture: {
      it: `Gestione Strutture`,
      en: `Facilities Management`,
      de: `Gebäudeverwaltung`
    },
    messaggio_nuovo_abbonato: {
      it: `Nessuna struttura ancora registrata.`,
      en: `No structure registered yet.`,
      de: `Noch kein Betrieb registriert.`
    },
    lista_strutture: {
      it: `Lista Strutture`,
      en: `Structures List`,
      de: `Liste der Betriebe`
    },
    nuova_struttura: {
      it: `Registra nuova Struttura`,
      en: `Register new facility`,
      de: `Neuen Betrieb registrieren`
    },
    gestisci: {
      it: `Gestisci`,
      en: `Manage`,
      de: `Verwalten`
    },
    acquisisci_licenza: {
      it: `Acquisisci Licenza Struttura`,
      en: `Acquire property's license`,
      de: `Erwerb Lizenz des Betriebes`
    },
    colonne: {
      nome: {
        it: `Nome`,
        en: `Name`,
        de: `Name`
      },
      territorio: {
        it: `Territorio`,
        en: `Territory`,
        de: `Gebiet`
      },
      zona: {
        it: `Zona`,
        en: `Area`,
        de: `Bereich`
      },
      località: {
        it: `Località`,
        en: `Location`,
        de: `Ort`
      },
      abbonamento: {
        it: `Abbonamento`,
        en: `Subscription`,
        de: `Abonnement`
      },
    },
    stati: {
      attivo: {
        it: `Attivo`,
        en: `Active`,
        de: `Aktiv`
      },
      annullato: {
        it: `Annullato`,
        en: `Canceled`,
        de: `Annulliert`
      },
      scaduto: {
        it: `Scaduto`,
        en: `Expired`,
        de: `Abgelaufen`
      },
      in_attesa_pagamento: {
        it: `In attesa pagamento`,
        en: `Waiting payment`,
        de: `Ausstehende Zahlung`
      },
      da_completare: {
        it: `Pagamento mancante`,
        en: `Missing payment`,
        de: `Fehlende Zahlung`
      },
      pagamento_non_riuscito: {
        it: `Pagamento non riuscito`,
        en: `Payment failed`,
        de: `Zahlung fehlgeschlagen`
      },
      in_prova: {
        it: `In prova`,
        en: `Try & Buy`,
        de: `auf Probe`
      }
    },
  }
</script>
