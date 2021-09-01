<?php

class Robot
{
    protected $x = 0;
    protected $xOperator = '+';
    protected $y = 0;
    protected $yOperator = '+';
    protected $finalDirection = self::NORTH;
    protected $command;
    protected $commands = [];
    protected $directions = ['North', 'South', 'East', 'West'];
    protected $walkingCommands;
    protected const NORTH = 0;
    protected const SOUTH = 1;
    protected const EAST = 2;
    protected const WEST = 3;

    /**
     * @param int $x
     * @param int $y
     */
    public function __construct(string $command)
    {
        $this->command = $command;
        preg_match_all('/W[0-9]+/', $command, $this->walkingCommands);
    }

    public function handle()
    {
        $this->parseCommand()
            ->executeCommand()
            ->output();
    }

    private function executeCommand()
    {
        foreach($this->commands as $key => $command){

            if($key == 0 && !in_array($command, ['R','L'])){
                $checker = $key;
                do{
                    $stepCount = str_replace('W','',$this->commands[$checker]);

                    $this->y = eval("return $this->y + $stepCount;");
                    $checker++;
                }while(!in_array($this->commands[$checker], ['R','L']));

                continue;
            }

            if($this->finalDirection == self::NORTH){
                if($command == 'R'){
                    $this->xOperator = '+';
                    $this->finalDirection = self::EAST;
                }

                elseif($command == 'L'){
                    $this->xOperator = '-';
                    $this->finalDirection = self::WEST;
                }

                $this->x += $this->evaluateStepsInDirection($this->xOperator,$key,$this->commands);
                continue;
            }

            if($this->finalDirection == self::WEST){
                if($command == 'R'){
                    $this->yOperator = '+';
                    $this->finalDirection = self::NORTH;
                }

                elseif($command == 'L'){
                    $this->yOperator = '-';
                    $this->finalDirection = self::SOUTH;
                }

                $this->y += $this->evaluateStepsInDirection($this->yOperator,$key,$this->commands);
                continue;
            }

            if($this->finalDirection == self::SOUTH){
                if($command == 'R'){
                    $this->xOperator = '-';
                    $this->finalDirection = self::WEST;
                }

                elseif($command == 'L'){
                    $this->xOperator = '+';
                    $this->finalDirection = self::EAST;
                }

                $this->x += $this->evaluateStepsInDirection($this->xOperator,$key,$this->commands);
                continue;
            }

            if($this->finalDirection == self::EAST){
                if($command == 'R'){
                    $this->yOperator = '-';
                    $this->finalDirection = self::SOUTH;
                }

                elseif($command == 'L'){
                    $this->yOperator = '+';
                    $this->finalDirection = self::NORTH;
                }

                $this->y += $this->evaluateStepsInDirection($this->yOperator,$key,$this->commands);

            }

        }
        return $this;
    }

    private function output()
    {
        printf("X: %d Y: %d Direction: %s.",$this->x,$this->y,$this->directions[$this->finalDirection]);
    }

    private function parseCommand()
    {
        for($i = 0; $i < strlen($this->command); $i++){
            switch($this->command[$i]){
                case 'R':
                    $this->commands[] = 'R';
                    break;
                case 'L':
                    $this->commands[] = 'L';
                    break;
                case 'W':
                    $this->commands[] = array_shift($this->walkingCommands[0]);
                    break;
                default:
                    break;
            }
        }
        unset($this->command);
        unset($this->walkingCommands);
        return $this;
    }

    private function evaluateStepsInDirection($direction,$key,$commands)
    {
        $checker = 1;
        $steps = 0;

        if(!in_array($commands[$key],['R','L'])){
            return;
        }

        while(isset($commands[$key+$checker]) && !in_array($commands[$key+$checker],['R','L'])){

            $stepCount = str_replace('W','',$commands[$key+$checker]);

            $steps = eval("return $steps $direction  $stepCount;");
            $checker++;
        }

        return $steps;
    }
}