<template>
    <div class="container mb-5">
        <div v-if="!gameFound"></div>
        <div v-else-if="isExpired">
            <h1>Game is Expired</h1>
        </div>
        <div v-else>
            <div class="m-5">
                <p>
                    <b>Game №:</b> {{game.id}}
                </p>
                <p>
                    <b>Username:</b> {{user.name}}
                </p>
                <p>
                    <b>Game Expired in:</b>
                    <Countdown :end="gameExpired" showDays showHours showMinutes showSeconds :endFunction="loadGame"></Countdown>
                </p>
                <div class="mt-2">
                    <button class="btn btn-info" @click="newGame">New Game</button>
                    <button class="btn btn-danger" @click="deleteGame"
                            v-confirm="{
                                loader: true,
                                ok: dialog => deleteGame(dialog),
                                cancel: null,
                                message: 'Are you sure?'
                        }"
                    >Delete This Game</button>
                </div>
            </div>
            <hr>
            <div>
                <button class="btn btn-secondary" @click="makeWin">Imfeelinglucky</button>
            </div>
            <div class="last-result" v-if="showLastWin">
                <p>
                    <b>Last Result:</b> {{lastResult}}
                </p>
                <p>
                    <b>Last Win:</b> <span :class="LastWinSum > 0 ? 'win' : 'lose'"> ${{ LastWinSum }} </span>
                </p>
            </div>
            <div class="mt-2">
                <button class="btn btn-secondary" @click="openHistory">History</button>
                <span class="close-btn" v-show="showHistory" @click="showHistory = false">Close</span>
            </div>
            <div class="last-result" v-if="showHistory">
                <div v-if="histories.length === 0">
                    No history else
                </div>
                <div v-else>
                    <h4>Last results:</h4>
                    <div v-for="history in histories" :key="history.id">
                        <p>
                            <b>Result:</b> {{history.result}}
                        </p>
                        <p>
                            <b>Win:</b> <span :class="history.win > 0 ? 'win' : 'lose'"> ${{ history.win.toFixed(2) }} </span>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import api from "../../config";
import Countdown from 'countdown-vue'

export default {
    name: "Game",
    components: { Countdown },
    data() {
        return {
            isExpired: false,
            gameFound: false,
            showHistory: false,
            showLastWin: false,
            lastResult: 0,
            LastWinSum: 0,
            user: {},
            game: {},
            histories: [],
        };
    },
    beforeRouteUpdate(to, from, next) {
        this.showHistory = false;
        this.showLastWin = false;
        this.gameFound = false;
        this.histories = [];
        this.loadGame(to.params.game);
        next();
    },
    computed: {
        gameExpired() {
            return this.game.expired_at;
        }
    },
    methods: {
        loadGame(defaultGameUrl = null) {
            this.showHistory = false;
            this.showLastWin = false;
            this.gameFound = false;
            let gameUrl = defaultGameUrl || this.$route.params.game;

            if (!gameUrl) {
                this.$router.push({name: 'notfound'});
                return;
            }

            let route = `user/games/${gameUrl}`;

            api.get(route).then((response) => {
                this.gameFound = true;
                this.user = response.data.game.user;
                this.game = response.data.game;
         //       this.watchGameChanel(this.game.id);
            }).catch(error => {
                if (error.response.status === 404) {
                    this.$router.push({name: 'notfound'});
                }
            });
        },
        makeWin() {
            let gameUrl = this.$route.params.game;
            let route = `user/games/${gameUrl}/win`
            api.post(route).then((response) => {
                this.lastResult = response.data.win.result;
                this.LastWinSum = response.data.win.win.toFixed(2);
                this.showLastWin = true;
                let messageResult = this.LastWinSum > 0
                    ? `You win $${this.LastWinSum} ! Your result ${this.lastResult} !`
                    : `You Lose :( Your result ${this.lastResult}`
                this.$dialog.alert(messageResult);
                if (this.showHistory) {
                    this.loadHistory();
                }
            }).catch(error => {
                if (error.response.status === 403) {
                    this.$dialog.alert('Game is Expired!').then(() => {
                        this.$router.push({name: 'notfound'});
                    });
                }
            });
        },
        async openHistory() {
            await this.loadHistory()
            this.showHistory = true;
        },
        loadHistory() {
            let gameUrl = this.$route.params.game;
            let route = `user/games/${gameUrl}/histories?limit=3`;

            api.get(route).then((response) => {
                this.histories = response.data.histories.data;
            }).catch(error => {
                if (error.response.status === 403) {
                    this.$dialog.alert('Game is Expired!').then(() => {
                        this.$router.push({name: 'notfound'})
                    });
                } else {
                    this.$dialog.alert('Something went wrong. Please, try later.');
                }
            });
        },
        deleteGame(dialog) {
            let gameUrl = this.$route.params.game;
            let route = `user/games/${gameUrl}`;
            api.delete(route).then((response) => {
                this.$dialog.alert('Game was deleted successfully').then(() => {
                    this.$router.push({name: 'home'});
                });
            }).catch(() => {
                this.$dialog.alert('Something went wrong. Please, try later.');
            }).finally(() => {
                dialog.close();
            });
        },
        newGame() {
            api.post('user/games', {
                user_id: this.user.id
            }).then((response) => {
                this.$dialog.alert('Game was created successfully').then(() => {
                    let newGame = response.data.game;
                    if (newGame && newGame.url) {
                        this.$router.push({name: 'game', params: {game: newGame.url}});
                    }
                });
            }).catch(() => {
                this.$dialog.alert('Something went wrong. Please, try later.');
            });
        },
    /*    watchGameChanel(id) {
            let channelName = 'game.' + id;

            window.Echo.channel(channelName)
                .listen('GameDestroyed', (e) => {
                    this.loadGame();
                })
        },*/
    },
    mounted() {
        this.loadGame();
    }
};
</script>

<style scoped>
.last-result {
    padding: 20px;
    border: 1px solid #d7d7d7;
    margin-top: 20px;
    border-radius: 10px;
}
.win {
    color: #2fad19;
}
.lose {
    color: #ed2525;
}
.close-btn {
    cursor: pointer;
    vertical-align: middle;
    color: gray;
    margin-left: 5px;
}
</style>
